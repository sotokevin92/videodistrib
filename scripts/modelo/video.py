import json
from datetime import datetime

class Video:
    id = None
    hash = None
    nombre_archivo = None
    descripcion = None
    vigente_desde = None
    vigente_hasta = None

    def __init__(self, id, hash, nombre, descripcion, vigente_desde, vigente_hasta):
        self.id = id
        self.hash = hash
        self.nombre_archivo = nombre
        self.descripcion = descripcion
        self.vigente_desde = vigente_desde
        self.vigente_hasta = vigente_hasta


    def __eq__(self, x):
        return self.hash == x.hash


    def __hash__(self):
        return int(self.hash, base=16)


    def __repr__(self):
        return "<" + self.__class__.__name__ + "> " + str(self)


    def __str__(self):
        return str(self.nombre_archivo) + ' (' + str(self.hash) + ')'


    def vigente(self):
        formato_fechahora = "%Y-%m-%d %H:%M:%S"
        if self.vigente_desde is not None:
            vigente_desde = datetime.strptime(self.vigente_desde, formato_fechahora)
        else:
            vigente_desde = datetime.min
        
        if self.vigente_hasta is not None:
            vigente_hasta = datetime.strptime(self.vigente_hasta, formato_fechahora)
        else:
            vigente_hasta = datetime.max
        
        return datetime.now() >= vigente_desde and datetime.now() < vigente_hasta


    @classmethod
    def parseJSON(cls, jsonstr):
        if isinstance(jsonstr, str):
            obj = json.loads(jsonstr)
        else:
            obj = jsonstr

        return Video(
            obj['id'],
            obj['hash'],
            obj['nombre_archivo'],
            obj['descripcion'],
            obj['vigente_desde'],
            obj['vigente_hasta']
        )
        