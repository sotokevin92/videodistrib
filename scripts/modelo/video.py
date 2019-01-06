import json

class Video:
    id = None
    hash = None
    nombre_archivo = None
    descripcion = None
    vigente = None

    def __init__(self, id, hash, nombre, descripcion, vigencia=True):
        self.id = id
        self.hash = hash
        self.nombre_archivo = nombre
        self.descripcion = descripcion
        self.vigente = vigencia


    def __eq__(self, x):
        return self.hash == x.hash


    def __hash__(self):
        return int(self.hash, base=16)


    def __repr__(self):
        return "<" + self.__class__.__name__ + "> " + str(self)


    def __str__(self):
        return str(self.nombre_archivo) + ' (' + str(self.hash) + ')'


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
            obj['vigente']
        )
        