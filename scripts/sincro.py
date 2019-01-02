from modelo.lista import Lista
from modelo.video import Video
import requests, queue, os, hashlib

class Sincro:
    """
    Clase de sincronización del cliente de reproducción.

    Contiene métodos para comunicarse con el servidor
    y chequear por cambios en la reproducción, comandos remotos y nuevos videos.
    """

    # URL del servidor
    URL_SERVER_BASE = None

    # URL base
    URL_SINCRO_BASE = 'pantalla'

    # ID cliente
    client_id = None

    # URL compuestas (completadas en constructor)
    URL_BEEPS = None
    URL_LISTA = None
    URL_FILES = None

    # Carpeta local de contenido para los archivos de video descargados
    CARPETA_VIDEOS_LOCAL = 'contenido'

    # Path absoluto a la carpeta local de contenido (completada en constructor)
    PATH_CONTENIDO = None

    # ---------------------

    # Última instancia de la lista de reproducción para este cliente
    lista = None

    # Cola de comunicación con el proceso principal
    principal = None

    def __init__(self, client_id, server_url, q=None):
        """
        Constructor principal. Toma como parámetros el client_id y la url del servidor,
        arma las URLs derivadas y las guarda en la instancia.
        """
        self.client_id = client_id
        self.URL_SERVER_BASE = server_url

        self.URL_BEEPS = '/'.join(
            [
                self.URL_SERVER_BASE,
                self.URL_SINCRO_BASE,
                self.client_id,
                'beacon'
            ]
        )

        self.URL_LISTA = '/'.join(
            [
                self.URL_SERVER_BASE,
                self.URL_SINCRO_BASE,
                self.client_id,
                'get_lista'
            ]
        )

        self.URL_FILES = '/'.join(
            [
                self.URL_SERVER_BASE,
                self.URL_SINCRO_BASE,
                self.client_id,
                'dl_video'
            ]
        )

        cwd = os.path.dirname(os.path.realpath(__file__))
        self.PATH_CONTENIDO = os.path.join(
            cwd,
            self.CARPETA_VIDEOS_LOCAL
        )

        self.lista = self.getListaRemota()
        self.principal = q


    def __str__(self):
        return str({
            'client_id': self.client_id,
            'URL_SERVER_BASE': self.URL_SERVER_BASE,
            'URL_SINCRO_BASE': self.URL_SINCRO_BASE,
            'URL_BEEPS': self.URL_BEEPS,
            'URL_LISTA': self.URL_LISTA,
            'URL_FILES': self.URL_FILES
        })


    def getListaRemota(self):
        """
        Obtener la lista de reproducción para este cliente desde el servidor.
        """
        r = requests.get(self.URL_LISTA)
        return Lista.parseJSON(r.json())


    def necesitoSincronizar(self):
        """
        Determina si se encontraron diferencias entre la lista local y la lista remota.
        """
        lista_nueva = self.getListaRemota()
        return not self.lista == lista_nueva


    def sincroArchivos(self):
        """
        Determinar las diferencias de archivos y sincronizar lo necesario.
        """
        if self.necesitoSincronizar():
            print("Actualizar lista...")
            self.lista = self.getListaRemota()

        cambios = self.verificarContenido()
        for archivo in cambios['eliminar']:
            print("Borrar " + archivo)
            os.unlink(
                os.path.join(self.PATH_CONTENIDO, archivo)
            )

        for id_video in cambios['descargar']:
            print("Descargar id " + str(id_video))
            self.descargarVideo(id_video)


    def verificarContenido(self):
        """
        Obtiene y verifica los archivos de la carpeta de acuerdo a la lista de reproducción.
        """
        retval = {
            'descargar': [],
            'eliminar': [],
        }

        if not os.path.exists(self.PATH_CONTENIDO):
            os.makedirs(self.PATH_CONTENIDO)

        dir_local = os.listdir(
            self.PATH_CONTENIDO
        )

        videos_local = {}

        for archivo in dir_local:
            videos_local[archivo] = self.getHashArchivo(os.path.join(self.PATH_CONTENIDO, archivo))

        videos_lista = self.lista.videos()
        for archivo in videos_lista:
            if videos_local[archivo] != videos_lista[archivo]['hash']:
                retval['eliminar'].append(archivo)
                retval['descargar'].append(videos_lista[archivo]['id'])

        for archivo in videos_local:
            if archivo not in videos_lista:
                retval['eliminar'].append(archivo)

        return retval


    def getHashArchivo(self, path_archivo):
        """
        Calcular la suma hash de un archivo dado
        TODO: mover a módulo auxiliar
        """
        este_hash = hashlib.sha1()
        with open(path_archivo, 'rb') as fd:
            while True:
                data = fd.read(65536)
                if not data:
                    break
                este_hash.update(data)

        return este_hash.hexdigest()


    def descargarVideo(self, id_video, nombre_archivo=None):
        url_videoinfo = '/'.join([self.URL_FILES, str(id_video)])

        r = requests.get(url_videoinfo).json()

        url_videofile = '/'.join([self.URL_SERVER_BASE, r['url']])
        if (nombre_archivo):
            nombre_guardar = nombre_archivo
        else:
            nombre_guardar = r['url'].split('/')[-1]

        r = requests.get(url_videofile)
        destino = os.path.join(self.PATH_CONTENIDO, nombre_guardar)
        with open(destino, "wb") as fd:
            fd.write(r.content)

        return r.status_code
