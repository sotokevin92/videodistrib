import sys, os, queue

from config import Configuracion
from sincro import Sincro
from player import Player

class Mensaje:
    origen = ''
    cmd = ''
    data = {}

    def __init__(self, origen, cmd, data={}):
        self.origen = origen
        self.cmd = cmd
        self.data = data


class VideoCliente:
    path_config = None

    q_player = None
    q_back = None

    CONFIG = None
    SINCRO = None
    PLAYER = None

    def __init__(self, config_file="default_cfg.json"):
        self.path_config = os.path.join(os.path.dirname(os.path.realpath(__file__)), config_file)

        if os.path.exists(self.path_config):
            self.CONFIG = self.getConfig(self.path_config)
        else:
            raise(Exception("Archivo de configuración no encontrado."))

        self.SINCRO = Sincro(self.CONFIG.client_id, self.CONFIG.server, self.CONFIG.subfolder_contenido)
        self.PLAYER = Player(self.CONFIG.player_cmd)

        self.q_player = queue.Queue()
        self.q_back = queue.Queue()


    def getConfig(self, nombre_archivo):
        return Configuracion.leerJSON(nombre_archivo)
        

    def hilo_reproduccion(self):
        orden = 0
        lista_ciclo = []
        while True:
            if not self.q_player.empty():
                msg = self.q_player.get()

                if msg.cmd == 'kill':
                    break
            
                if msg.cmd == 'stop':
                    if self.CONFIG.idle_file:
                        lista_ciclo = [ self.CONFIG.idle_file ]
                    else:
                        self.PLAYER.stop()

                    orden = 0

                    self.q_back.put(
                        Mensaje(
                            'PLAYER',
                            'Detenido'
                        )
                    )
                    self.PLAYER.setEstado(0)
                    continue
                
                if msg.cmd == 'panic':
                    self.PLAYER.stop()

                    orden = 0

                    self.q_back.put(
                        Mensaje(
                            'PLAYER',
                            'Detenido (sin idle play)'
                        )
                    )
                    self.PLAYER.setEstado(0)
                    continue

                if msg.cmd == 'update' or msg.cmd == 'list':
                    lista_ciclo = msg.data
                    orden = 0

                    self.q_back.put(
                        Mensaje(
                            'PLAYER',
                            'Aprovisionamiento de lista',
                            lista_ciclo
                        )
                    )
                    continue
                
                if msg.cmd == 'play':
                    self.PLAYER.setEstado(1)
            
            if lista_ciclo.__len__() == 0:
                # Nada que hacer..
                continue

            if self.PLAYER.reproduciendo():
                continue

            orden = orden + 1
            if orden > lista_ciclo.__len__():
                orden = 1

            indice = orden - 1

            self.PLAYER.play(lista_ciclo[indice])

            if self.PLAYER.estado != 0:
                self.q_back.put(
                    Mensaje(
                        'PLAYER',
                        'Reproduciendo',
                        {
                            'archivo': lista_ciclo[indice],
                            'orden': orden
                        }
                    )
                )
        self.q_back.put(
            Mensaje(
                'PLAYER',
                'Killed by cmd'
            )
        )
