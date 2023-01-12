<!-- PROJECT LOGO -->
<br />
<p align="center">
  <a href="https://github.com/quarkmarino/zip-endpoint">
    <img src="logo.png" alt="Logo" width="80" height="80">
  </a>

  <h3 align="center">Reto Tecnico</h3>

  <p align="center">
    Utilizar el framework Laravel para replicar la funcionalidad de esta api:
    <br />
    <a href="https://jobs.backbonesystems.io/api/zip-codes/01210">https://jobs.backbonesystems.io/api/zip-codes/01210</a>
  </p>

  <p align="center">
    API end-point del reto técnico:
    <br />
    <a href="https://backbonesystems.marianoescalera.me/api/zip-codes/01210">https://backbonesystems.marianoescalera.me/api/zip-codes/01210</a>
  </p>
</p>

<!-- TABLE OF CONTENTS -->
<details open="open">
  <summary>Tabla de Contenidos</summary>
  <ol>
    <li><a href="#instrucciones">Instrucciones</a></li>
    <li><a href="#evaluacion">Evaluación</a></li>
    <li><a href="#descripcion-analisis">Descripción del Análisis</a></li>
    <li><a href="#importacion-catalogo">Importación del Catálogo</a></li>
    <li><a href="#devolviendo-representacion">Devolviendo la Representación</a></li>
    <li><a href="#structure-the-data">Structure the data</a></li>
    <li><a href="#opinions-and-hypotetical-observaions">Opinions and hypothetical observations</a></li>
    <li><a href="#more-information">More Information</a></li>
    <li><a href="#instalation">Installation</a></li>
    <li><a href="#screenshots">Screenshots</a></li>
    <li><a href="#license">License</a></li>
    <li><a href="#contact">Contact</a></li>
  </ol>
</details>

# Instrucciones

El reto consiste en utilizar el framework Laravel para replicar la funcionalidad de esta api
(https://jobs.backbonesystems.io/api/zip-codes/01210), utilizando esta (https://www.correosdemexico.gob.mx/SSLServicios/ConsultaCP/CodigoPostal_Exportar.aspx) fuente de información.
El tiempo de respuesta promedio debe ser menor a 300 ms, pero entre menor sea, mejor.
[GET] https://jobs.backbonesystems.io/api/zip-codes/{zip_code}
NOTA: Es importante recalcar que si no tiene la sintaxis anterior, no se contará como correcta y por tanto no se podrá evaluar.

Se deber publicar el API para que pueda ser probado. En caso de que funcione con las
instrucciones presentadas a continuacion, se procedera a revisar el codigo, el cual deber ser
compartido a traves de un repositorio publico en cualquier plataforma de git. El challenge se
evalúa automáticamente. En caso de que apruebes el challenge, te llegar un correo por parte
de nuestro equipo de reclutamiento para indicar los pasos a seguir.

## Evaluación

Deberás probar tus tiempos de respuesta localmente y en un servicio público. Cuando te
sientas listo entra a https://jobs.backbonesystems.io/challenge/1 en donde llenaras el
formulario para calificar tu endpoint.

Los endpoints no válidos son: 127.0.0.1, localhost:XXXX, test.xxxx, 0.0.0.0:xxxx. Debido a que
no son una IP pública a la que podamos acceder.

Si está a través de un tunnel de expose, ngrok o tunnel.me tampoco podremos evaluarlo, debe
ser una IP Pública y accesible sin uso de túneles.

El programa evaluará tu endpoint y te entregará tiempos de respuesta por cada uno de los
códigos postales evaluados. NOTA: Son elegidos de manera aleatoria.

## Descripción del Análisis

La API consta de un solo end-point o ruta que se puede visitar por medio del protocolo HTTP
utilizando el metodo GET.

La ruta valida es la siguiente /api/zip-codes/{zip_code}
donde el valor del {zip_code} se debe reemplazar for un valor numerico nominal de 5 digitos exactamente,
correspondiente al valor de algun Código Postal del País, segun el catalogo de códigos postales de la SEPOMEX.

Al visitar la ruta proveiendo un código postal valido, p.ej. 68000, esta debe devolver la representación del
registro correspondiente en formato JSON con la siguiente estructura:

```json
{
    "zip_code": "68000",
    "locality": "OAXACA DE JUAREZ",
    "federal_entity": {
        "key": 20,
        "name": "OAXACA",
        "code": null
    },
    "settlements": [
        {
            "key": 1,
            "name": "OAXACA CENTRO",
            "zone_type": "URBANO",
            "settlement_type": {
                "name": "Colonia"
            }
        }
    ],
    "municipality": {
        "key": 67,
        "name": "OAXACA DE JUAREZ"
    }
}
```

De acuerdo al catálogo de códigos postales de la sepomex (sugerido en las instrucciones),
los valores de las columnas provistos por este, son los siguientes:

    - d_codigo          Código Postal asentamiento
    - d_asenta          Nombre asentamiento
    - d_tipo_asenta     Tipo de asentamiento (Catálogo SEPOMEX)
    - D_mnpio           Nombre Municipio (INEGI, Marzo 2013)
    - d_estado          Nombre Entidad (INEGI, Marzo 2013)
    - d_ciudad          Nombre Ciudad (Catálogo SEPOMEX)
    - d_CP              Código Postal de la Administración Postal que reparte al asentamiento
    - c_estado          Clave Entidad (INEGI, Marzo 2013)
    - c_oficina         Código Postal de la Administración Postal que reparte al asentamiento
    - c_CP              Campo Vacio
    - c_tipo_asenta     Clave Tipo de asentamiento (Catálogo SEPOMEX)
    - c_mnpio           Clave Municipio (INEGI, Marzo 2013)
    - id_asenta_cpcons  Identificador único del asentamiento (nivel municipal)
    - d_zona            Zona en la que se ubica el asentamiento (Urbano/Rural)
    - c_cve_ciudad      Clave Ciudad (Catálogo SEPOMEX)

y donde el ejemplo del registro correspondiente provisto por el catalogo muestra los siguiente

```CSV
d_codigo|d_asenta     |d_tipo_asenta|D_mnpio         |d_estado|d_ciudad        |d_CP |c_estado|c_oficina|c_CP|c_tipo_asenta|c_mnpio|id_asenta_cpcons|d_zona|c_cve_ciudad
68000   |Oaxaca Centro|Colonia      |Oaxaca de Juárez|Oaxaca  |Oaxaca de Juárez|68005|20      |68005    |    |09           |067    |0001            |Urbano|02
```

Al analizar ambos ejemplos podemos identificar las correlaciones entre los campos de la
representasión en formato JSON y la de CSV de manera que podemos asumir un mapeo entre los valores
como el siguiente:

```JSON
{
    "zip_code": d_codigo,
    "locality": d_ciudad,
    "federal_entity": {
        "key": c_estado,
        "name": d_estado,
        "code": null
    },
    "settlements": [
        {
            "key": id_asenta_cpcons,
            "name": d_asenta,
            "zone_type": d_zona,
            "settlement_type": {
                "name": d_tipo_asenta
            }
        }
    ],
    "municipality": {
        "key": c_mnpio,
        "name": D_mnpio
    }
}
```

Asi mismo se pueden identificar 4 ejes de datos:

- El Codigo Postal (Raíz)
- La Entidad Federativa (federal_entity)
- La colección de Asentamientos (settlements)
- El municipio (municipality)

Cada uno de estos ejes se representara con su modelo correspondiente en la aplicacion, dando lugar a los siguientes:

- CodigoPostal
- EntidadFederativa
- Municipio
- Asentamiento

Relacionados de la siguiente forma:

- CodigoPostal pertenece a (belongsTo) EntidadFederativa
- CodigoPostal pertenece a (belongsTo) Municipio
- CodigoPostal tiene muchos (hasMany) Asentamientos

## Importación del Catálogo

Para poder proveer las representaciones de manera eficiente se ha realizado
la importacion del catalogo completo descargado en formato CSV (separador `|`)

El paquete de maatwebsite/excel provee excelentes heramientas para la apertura
y el procesamiento de los registros del CSV del cátalogo.

La clase de importación:
- `App/Imports/CodigosPostalesImport`

es llamada desde el comando
- `app/Console/Commands/CodigosPostalesImportCommand`

por medio de la ejecución de la siguiente instrucción
- `php artisan import:codigos_postales`

La clase de importación lee el archivo del disco `assets`
previamente configurado en la seccion de discos `disks` del archivo de configuracion `config/filesystems`

La importación es un porceso de creacion de todos los modelos requeridos por cada registro del catálogo
Si el model exacto ya existe en la base de datos es extraido y reutilizado en la creación de los modelos subsecuentes

> Para el caso, alguna optimización extra en el procesamiento de importación del catálogo es de poco ROI por el momento
aunque deberian haber algunas optimizaciones posibles para esta operación.

### Migraciones

Las migraciones implementan tipos y tamaños de datos apropiados, asi como un indexado de las columnas mas importantes

# Devolviendo la Representación

# Acciones

Hay 3 acciones (altenativas) disponibles para obtener el resultado de la representación de un código postal

- viaPureEloquent
    - Consta de una consulta contruida por medio del ORM Eloquent
        - Utiliza el mecanismo de carga previa de relaciones (eager loading) para las 3 relaciones
        - Ejecuta 4 consulta a la base de datos para obtener los registros necesarios
            - 1 consulta para el modelo principal (CodigoPostal)
            - 3 consultas, una para cada modelo relacionado
- viaQueryBuilder
    - Consta de una consulta construida por medio del QueryBuilder de laravel
        - Utiliza expresiones "crudas" (raw expressions), para construir la estructura JSON directament en la base de datos
        - Ejecuta "una sola" consulta con:
            - 2 joins
            - 1 sub-query
        - El mas eficiente
- viaEloquentAndPowerJoins (el principal, enviado a comprobación para el reto técnico)
    - Consta de una consulta construida por medio del ORM Eloquent
        - Utiliza el mecanismo de carga previa de relaciones (eager loading) solamente para 1 relación (asentamientos)
        - Utiliza PowerJoins provistas por el paquete "kirschbaum-development/eloquent-power-joins"
            - Este paquete, solo provee una manera mas abstracta de agregar joins a las queries (`Syntactic sugar` se podria decir) por medio de las definicioes de las relaciones ya implementadas en el model
        - Ejecuta 2 queries
            - 1 consulta para el modelo principal con joins a las 2 tablas relacionadas por medio del `belongsTo` (entidadFederativa, municipio)
            - 1 consulta para el modelo relacionado por medio del `hasMany` (asentamientos)

Cada mecanismo tiene sus ventajas y desventajas, por ejemplo:

- viaPureEloquent
    - (+) El mas claro de leer y compacto
    - (-) El mas ineficiente (4 consultas), solo nos previene del problema `n + 1`
- viaQueryBuilder
    - (+) El mas eficiente
    - (-) El mas complejo de leer y modificar
- viaEloquentAndPowerJoins
    - (+) El mas balanceado

Aunque en realidad todos son bastante eficientes, gracias al manejo de indices en la base de datos
todas las consultas a nivel de end-point, responden en menos de 85ms de manera externa y 15ms de manera interna en promedio.

Externa
<img src="screenshots/Screenshot from 2023-01-12 03-06-54.png" alt="Pruebas Externas">

Interna
<img src="screenshots/Screenshot from 2023-01-12 03-07-03.png" alt="Pruebas Internas">

# Armado de la representación

Hay 2 maneras implementadas para la creación de la representación en JSON correspondiente al código postal obtenido:

- Inmediato, por medio del DBMS utilizando `JSON_OBJECT` y `JSON_ARRAYAGG`
    - Lo implementa `viaQueryBuilder`
- Por medio de EloquentResources y Presenters
    - Lo implementan `viaPureEloquent` y `viaEloquentAndPowerJoins`

## EloquentResources

Cuando la respuesta de la consulta es una instancia de un model Eloquent, la manera mas conveniente de
transformar el recurso en una respuesta de tipo JSON, es por medio de un Recurso de Eloquent

De esta manera, se procesa el modelo CodigoPostal y se le da la estructura apropiada
mapeando los valores internos del recurso a los requeridos por la definición de la estructura.

Dado que los mecanismos de consulta de `viaPureEloquent` y `viaEloquentAndPowerJoins` devuelven una
estructura de datos ligeramente distinta, el `ZipCodeResource` revisa si las relaciones `entidadFederativa` y 'municipio'
se ha cargado y llama al Recurso Eloquent con el modelo respectivo.

## Presenters

Para replicar la funcionalidad de la API indicada, de la manera mas fiel posible, pero sin abultar la logica de la conversión
en los Modelos (por medio de Accessors) o en los Controlladores (encargandoles tareas fuera de su responsabilidad).

La mejor manera de abordar dicha manipulacion de los valores parece ser por medio de un Presentador (Patrón de diseño)
Por medio del cual podemos tomar alguna instancia, en este caso el `Recurso Eloquent`,
notar que no es el `Modelo Eloquent`, aunque tambien podria ser presentado de una manera similar,
e indicarle, por medio de que presentador van a ser presentados sus valores.

Para hacer uso del patron de diseño de presetador, en este caso, le indicamos a la clase que implemente el
contrato `App/Services/Presenter/Contracts/Presentable`, y le proveemos la funcionalidad por medio del
trait `App/Services/Presenter/Traits/HasPresenter`, de esta manera, podemos indicar una propiedad que contendra el nombre de
la clase presentadora por default, p.ej. `protected $presenter = ZipCodePresenter::class;`

Por estos medios, ahora podemos llamar al metodo `present()` sobre la instancia presentable y obtener el resultado de
algun metodo que haya sido implementado en el la clase presentadora, por medio de metodos magicos `__get` y `__call` en la clase presenter, por ejemplo:
    - En `App/Http/Resources/ZipCodeResource`, podemos `$this->present()->locality`, llama al metodo `locality()` sobre la clase `App/Services/Presenter/Presenters/ZipCodePresenter`
    - En `App/Http/Resources/SettlementResource`, podemos `$this->present()->zone_type`, llama al metodo `zone_type()` sobre la clase `App/Services/Presenter/Presenters/SettlementPresenter`
    - etc.

Para el caso de estos presentadores, las operaciones son sumamente simples, solamente convierten los caracteres del
valor del attributo del modelo, en caracteres valores ASCCI aproximados, (p.ej. í en i, 'Ñ' en '?' etc.)
> Tal vez no es una caracteristica intencional (probablemente por el encoding de la base de datos al importar los registros del cátalogo), pero vale la pena indicar en que se considero ese detalle y se resolvio de una manera elegante.

## El Caso del "Tipo de Asentamiento" y Enums

El valor del `d_tipo_asenta` y `d_zona`, son casos interesantes, ya que son valores categoricos de un conjunto relativamente pequeño,
y son ideales para ser modelados como Enums (php8.1), sin embargo para el caso en cuestion, proveen muy poco valor como para implementarlos inmeditamanete, (seria una "optimizacion prematura")

Respecto al tipo de asentamiento del modelo asentamiento, su valor literal (d_tipo_asenta) y numerico (c_tipo_asenta) son almacenados junto con el asentamiento
lo cual efectivamente podria ser normalizado a otro tabla, ya que son columnas con baja cardinalidad,
o mejor aun, solamente almacenar el valor literal (d_tipo_asenta) en la tabla de asentamientos y castear dicho valor al Enum correspondiente
aunque bajo una lente pragmatica, dicha optimización no parece realmente necesaria para cumplir con los requerimientos de la prueba.

## Testing y tiempos de Respuesta

Se hay hecho varias consideraciones desde el analisis, hasta la implementación para optimizar
la velocidad de respuesta del end-point de la API, y se han implementado pruebas para confirmar el cumplimiento de dicho requerimiento,
tambien para confirmar (en la medida de lo posible) que la respuesta provista por la API implementada es exactamente igual a la de referencia.

Por lo que 2 pruebas han sido diseñadas
- Internal
    - Evaluan el tiempo de respuesta ideal de la API, asi como el comportamiento ante parametros invalidos y la similitud con la APi de referencia.
- External
    - La razon principal de esta prueba es confirmar que la aplicacion responde en menos de 300ms
siendo visitada desde algun punto suficientemente cercano y con poca latencita, ya que las pruebas realizadas de
manera manual (postman y curl) arrojan resultados de tiempos extremos en la primera carga,
tanto para la API de reto como para la de referencia.

Desde mi ISP (Postman)
<img src="screenshots/Screenshot from 2023-01-12 03-07-03.png" alt="Velocidad de respuesta https://jobs.backbonesystems.io/api/zip-codes/10630">
<img src="screenshots/Screenshot from 2023-01-12 03-12-01.png" alt="Velocidad de respuesta https://backbonesystems.marianoescalera.me/api/zip-codes/10630">

Desde un VPS (Linode)
<img src="screenshots/Screenshot from 2023-01-12 03-16-41.png" alt="Velocidad de respuesta ambos end-points">

# Mas Información (otras experiencias)

Esta no es la primera vez que realizo la importación, procesamiento e implementación de una API de los datos de códigos postales de la SEPOMEX,
durante el desarrollo de una aplicación para el manejo y administración de nóminas, tuve que cargarlos y ponerlos a disposicion
por medio de una API para su busqueda de manera similar para el consumo del modulo de direcciones de los trabajadores y las empresas implementado en VueJs

Tambien he realizado la importación de otros catalogos:
    - SAT (catCFDI.xls y catNomina.xls) completos
    - INEGI (entidad, localidad, municipio, agrupacionOcupacional)
        - SINCO (documento_sinco_2018.pdf), si del PDF!
    - SIRCE (capacitacion, competencia, curso, modalidad, ocupacion, etc.)
    - SCT (Estructura, Localidad, RedVial, SitioDeInteres, PlazaCobro, etc.)

### Instalación

1. Clone the repo
    ```sh
    git clone https://github.com/quarkmarino/zip-endpoint
    ```
2. Install Composer dependencies
    ```sh
    cd zip-endpoint
    composer install
    ```
3. Fire Up Sail (requires Docker)
    ```sh
    sail up -d
    ```
4. Run artisan seeds (importara toda la base de datos)
    ```sh
    sail art db:seed
    ```
5. Visit http://localhost/api/zip-codes


<!-- LICENSE -->
## License

Distributed under the MIT License. See `LICENSE` for more information.

<!-- CONTACT -->
## Contact

Jose Mariano Escalera Sierra - [@quarkmarino](https://twitter.com/quarkmarino) - mariano.pualiu@gmail.com

Project Link: [https://github.com/quarkmarino/zip-endpoint](https://github.com/quarkmarino/zip-endpoint)

