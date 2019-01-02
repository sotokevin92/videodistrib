import sys, os, queue

from config import Configuracion
from sincro import Sincro
from player import Player

class VideoCliente:
    path_config = None

    CONFIG = None
    SINCRO = None
    PLAYER = None

    def __init__(self, config_file="cfg.json"):
        self.path_config = os.path.join(os.path.dirname(os.path.realpath(__file__)), config_file)

        if os.path.exists(self.path_config):
            self.CONFIG = self.getConfig(self.path_config)
        else:
            raise(Exception("Archivo de configuración no encontrado."))

        self.SINCRO = Sincro(self.CONFIG.client_id, self.CONFIG.server)
        self.PLAYER = Player(self.CONFIG.player_cmd)


    def getConfig(self, nombre_archivo):
        return Configuracion.leerJSON(nombre_archivo)


    def checkSincro(self):
        # TODO: comparar playlists remota y local
        # TODO: comparar listados de directorio de la carpeta local y la carpeta remota
        # TODO: devolver si es necesario sincronizar
        pass

    def sincronizar(self, nombre, clean=False):
        # TODO: detener la reproducción local
        # TODO: borrar videos de la carpeta local que no estén en el server
        # TODO: descargar videos del server que no estén en la carpeta local
        pass


    def reproducir(self):
        # TODO: obtener listado de archivos en la carpeta local
        # TODO: verificar cola de comandos
        # TODO: shuffle en lista de archivos

        pass


app = VideoCliente()

exit(0)
