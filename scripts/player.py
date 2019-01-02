import os
from subprocess import Popen

class NoSePuedeEjecutarException(Exception):
    pass


class Player:
    # Directiva de linea de comandos para la llamada al sistema de la ejecución.
    cmd = None
    proceso = None

    def __init__(self, cmd):
        self.cmd = cmd

    def play(self, path):
        """
        Reproducir un archivo utilizando la directiva de comando de la instancia.
        Guardar la información del subproceso en el atributo proceso.
        """

        # Detener la reproducción preventivamente
        self.detener()

        # Armar directiva de comando
        cmd_exec = self.cmd.replace("_FILE_", path)

        # Silenciar salida estándar y error
        with open(os.devnull, 'w') as FNULL:
            self.proceso = Popen(cmd_exec, stdout=FNULL, stderr=FNULL)

    def detener(self):
        """
        Envía la señal de terminación al proceso actual, si lo hay.
        """

        if self.proceso is not None and self.proceso.poll() is None:
            self.proceso.kill()
