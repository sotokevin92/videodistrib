from modelo.lista import Lista
from modelo.video import Video
import requests, os, hashlib, json

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
    CARPETA_VIDEOS_LOCAL = None

    # Path absoluto a la carpeta local de contenido (completada en constructor)
    PATH_CONTENIDO = None

    # ---------------------

    # Última instancia de la lista de reproducción para este cliente
    lista = None

    def __init__(self, client_id, server_url, carpeta_local='contenido'):
        """
        Constructor principal. Toma como parámetros el client_id y la url del servidor,
        arma las URLs derivadas y las guarda en la instancia.
        """
        self.client_id = client_id
        self.CARPETA_VIDEOS_LOCAL = carpeta_local

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

        self.lista = self.ultimaLista()

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

        Devuelve un diccionario con claves 'json' y 'lista'.
        """
        r = requests.get(self.URL_LISTA)
        if r.status_code == 200:
            return {
                'json': r.json(),
                'lista': Lista.parseJSON(r.json())
            }
        
        return {
            'json': 'offline',
            'lista': Lista()
        }

    
    def ultimaLista(self):
        """
        Obtener la lista de reproducción para este cliente localmente.
        """
        try:
            with open("ult_lista.json", "r") as fd:
                return Lista.parseJSON(json.load(fd))
        except:
            return Lista()

    def necesitoSincronizar(self):
        """
        Determina si se encontraron diferencias entre la lista local y la lista remota.
        """
        lista_nueva = self.getListaRemota()
        if lista_nueva['json'] == 'offline':
            return False

        lista_nueva_lista = lista_nueva['lista']
        return not self.lista == lista_nueva_lista

    def sincronizar(self):
        """
        Determinar las diferencias de archivos y sincronizar lo necesario.

        TODO: cancelar la sincro o reintentar si falla una descarga
        """
        if self.necesitoSincronizar():
            print("Actualizar lista...")
            val = self.getListaRemota()
            self.lista = val['lista']
            with open("tmp_lista.json", "w") as fd:
                json.dump(val['json'], fd)

        cambios = self.verificarContenido()
        for id_video in cambios['descargar']:
            print("Descargar id " + str(id_video))
            self.descargarVideo(id_video)

        for archivo in cambios['eliminar']:
            print("Borrar " + archivo)
            os.unlink(
                os.path.join(self.PATH_CONTENIDO, archivo)
            )

        cambios = self.verificarContenido()

        return cambios['descargar'].__len__() == 0


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
            if archivo not in videos_local:
                retval['descargar'].append(videos_lista[archivo]['id'])
                continue

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
        """
        este_hash = hashlib.sha1()
        with open(path_archivo, 'rb') as fd:
            while True:
                data = fd.read()
                if not data:
                    break
                este_hash.update(data)

        return este_hash.hexdigest()

    def descargarVideo(self, id_video, nombre_archivo=None):
        url_videoinfo = '/'.join([self.URL_FILES, str(id_video)])
        try:
            r = requests.get(url_videoinfo).json()

            url_videofile = r['url']
            if (nombre_archivo):
                nombre_guardar = nombre_archivo
            else:
                nombre_guardar = r['url'].split('/')[-1]

            r = requests.get(url_videofile)
            destino = os.path.join(self.PATH_CONTENIDO, nombre_guardar)
            with open(destino, "wb") as fd:
                fd.write(r.content)

            return True
        except:
            return False

    def registrarBeep(self, id_lista, nro):
        try:
            requests.post(self.URL_BEEPS, {
                'id_lista': id_lista,
                'orden': nro
            })

            return True
        except: 
            return False

    def getListaLocal(self):
        retlista = []
        for video in self.lista.entries:
            fullpath = os.path.join(self.CARPETA_VIDEOS_LOCAL, video.nombre_archivo)
            if ' ' in fullpath:
                if os.name == 'nt':
                    fullpath = '"' + fullpath + '"'
                else:
                    fullpath = fullpath.replace(' ', '\ ')
            
            retlista.append(fullpath)
        
        return retlista

    def testearConexion(self):
        try:
            requests.head(self.URL_SERVER_BASE)
            return True
        except:
            return False
