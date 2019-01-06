import os, json

class Configuracion:
    """
    Clase de Configuración para el cliente de reproducción.
    Guarda data como la linea de comandos a ejecutar por el proceso principal.
    """

    # Guardar el string JSON original
    el_json = None

    # Dirección URL del servidor
    server = None

    # client_id es la subempresa
    client_id = None

    # Comando a ejecutar para reproducir un video
    player_cmd = None

    # Video a reproducir cuando la reproducción principal esté detenida
    idle_file = None

    # Subcarpeta local para descargar y reproducir los archivos de video
    subfolder_contenido = None

    def __init__(self, la_data):
        """
        Constructor principal.

        Pre-condiciones:
            la_data = objeto en formato JSON con datos de configuración.
            la_data tiene opciones válidas.

        Pos-condiciones:
            Configuración cargada en atributos de instancia.
        """
        self.el_json = str(la_data)
        self.server = la_data['server']
        self.client_id = la_data['client_id']
        self.player_cmd = la_data['cmd']
        if os.path.isfile(os.path.realpath(la_data['idle_file'])):
            self.idle_file = os.path.realpath(la_data['idle_file'])
        else:
            self.idle_file = None
        
        self.subfolder_contenido = os.path.realpath(la_data['subfolder_contenido'])


    def __str__(self):
        """
        Representación en cadena de la configuración. Devuelve el JSON original.
        """
        return self.el_json


    @classmethod
    def leerJSON(cls, path):
        """
        Método de clase para recuperar la información JSON desde un archivo.

        Pre-condiciones:
            Archivo de configuración existe.

        Pos-condiciones:
            cfg = datos de configuración del archivo ó valores por defecto.
            cfg tiene opciones válidas.
            Nueva instancia de configuración creada en base a cfg.
        """
        config = {}
        with open(path, 'r') as fd:
            config = json.load(fd)

        # TODO: chequear integridad y validar
        return Configuracion(config)
