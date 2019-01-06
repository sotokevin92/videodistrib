import json
from .video import Video

class Lista:
    nombre = None
    entries = []
    vigente_desde = None
    vigente_hasta = None

    def __init__(self, id=-1, nombre='', entries=[], vigente_desde=None, vigente_hasta=None):
        self.id = id
        self.nombre = nombre
        self.entries = entries
        self.vigente_desde = vigente_desde
        self.vigente_hasta = vigente_hasta
        

    def __eq__(self, x):
        actual = self.entries
        nueva = x.entries

        if actual.__len__() != nueva.__len__():
            return False

        for i in range(0, actual.__len__()):
            if actual[i] != nueva[i]:
                return False

        return True


    def __repr__(self):
        return repr(self.entries)


    def videos(self):
        archivos_lista = {}
        for entry in list(set(self.entries)):
            archivos_lista[entry.nombre_archivo] = { 'hash': entry.hash, 'id': entry.id }

        return archivos_lista


    @classmethod
    def parseJSON(cls, jsonstr):
        """
        Método de clase para mapear los datos JSON a una instancia de Lista.
        """
        if isinstance(jsonstr, str):
            obj = json.loads(jsonstr)
        else:
            obj = jsonstr

        if not 'lista' in obj:
            raise(Exception("Datos JSON no válidos"))
        
        if not 'entries' in obj['lista']:
            raise(Exception("Datos JSON no válidos"))
        
        lista = obj['lista']
        entries = []

        for entry_json in lista['entries']:
            # Parsear entries:
            entries.append(
                Video.parseJSON(entry_json['video'])
            )

        return Lista(
            lista['id'],
            lista['nombre'],
            entries,
            lista['vigente_desde'],
            lista['vigente_hasta']
        )
