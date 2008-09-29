/*==============================================================*/
/* Database name:  DIFIORI_MYSQL                                */
/* DBMS name:      MySQL 3.23                                   */
/* Created on:     11/08/2008 04:48:07 p.m.                     */
/*==============================================================*/


drop table if exists BANDERAS;

drop table if exists CANALES;

drop table if exists CARGAS;

drop table if exists CLIENTES;

drop table if exists DESTINACIONES;

drop table if exists ES_CONTRATADO_POR;

drop table if exists EXPORTACIONES;

drop table if exists GIROS;

drop table if exists IMPORTACIONES;

drop table if exists MEDIOS;

drop table if exists MONEDAS;

drop table if exists OPP;

drop table if exists PASA_POR;

drop table if exists PROVEEDOR_MEDIO;

drop table if exists PUERTOS;

drop table if exists TABLAS;

drop table if exists TRANSPORTES;

drop table if exists USUARIOS;

drop table if exists IDIOMAS;

drop table if exists USUARIO_MODIFICA_TABLA;

/*==============================================================*/
/* Table: BANDERAS                                              */
/*==============================================================*/
create table BANDERAS
(
   CODIGO_BAN                     int                            not null,
   NOMBRE_BAN                     varchar(150)                   not null,
   primary key (CODIGO_BAN)
);

/*==============================================================*/
/* Table: CANALES                                               */
/*==============================================================*/
create table CANALES
(
   CODIGO_CAN                     char(1)                        not null,
   DESCRIPCION_CAN                varchar(20)                    not null,
   primary key (CODIGO_CAN)
);

/*==============================================================*/
/* Table: CARGAS                                                */
/*==============================================================*/
create table CARGAS
(
   CODIGO_CAR                     int                            not null,
   CANTBULTOS_CAR                 int                            not null,
   TIPOENVASE_CAR                 varchar(100)                   not null,
   PESOBRUTO_CAR                  float                          not null,
   UNIDAD_CAR                     varchar(30)                    not null,
   NROPAQUETE_CAR                 varchar(25),
   MARCAYNUMERO                   varchar(100),
   MERC__IMCO                     varchar(100),
   primary key (CODIGO_CAR)
);

/*==============================================================*/
/* Table: CLIENTES                                              */
/*==============================================================*/
create table CLIENTES
(
   CODIGO_CLI                     int AUTO_INCREMENT             not null,
   NOMBRE_CLI                     varchar(200)                   not null,
   DIRECCION_CLI                  varchar(200),
   CODIGOPOSTAL_CLI               varchar(15),
   LOCALIDAD_CLI                  varchar(150),
   CUIT_CLI                       char(13),
   TIPOIVA_CLI                    varchar(100),
   TIPOCLIENTE_CLI                varchar(100),
   primary key (CODIGO_CLI)
);

/*==============================================================*/
/* Table: DESTINACIONES                                         */
/*==============================================================*/
create table DESTINACIONES
(
   CODIGO_DES                     int AUTO_INCREMENT              not null,
   DESCRIPCION_DES                varchar(150),
   primary key (CODIGO_DES)
);

/*==============================================================*/
/* Table: ES_CONTRATADO_POR                                     */
/*==============================================================*/
create table ES_CONTRATADO_POR
(
   CODIGO_BUQ                     int                            not null,
   CODIGO_TRA                     int                            not null,
   primary key (CODIGO_BUQ, CODIGO_TRA)
);

/*==============================================================*/
/* Table: EXPORTACIONES                                         */
/*==============================================================*/
create table EXPORTACIONES
(
   ORDEN                          int                            not null,
   CODIGO_TRA                     int                            not null,
   CODIGO_CLI                     int                            not null,
   CODIGO_BAN                     int                            not null,
   CODIGO_MON                     char(3)                        not null,
   CODIGO_GIR                     int,
   CODIGO_DES                     int                            not null,
   CODIGO_CAR                     int                            not null,
   REFERENCIA                     varchar(40),
   FECHAINGRESO                   date                           not null,
   DESCMERCADERIA                 varchar(200)                   not null,
   VALORFACTURA                   double,
   VENCIMIENTO                    date                           not null,
   INGRESOPUERTO                  date,
   PER_NRODOC                     varchar(30)                    not null,
   PER_PRESENTADO                 date                           not null,
   PER_FACTURA                    varchar(40),
   PER_FECHAFACTURA               date,
   primary key (ORDEN)
);

/*==============================================================*/
/* Table: GIROS                                                 */
/*==============================================================*/
create table GIROS
(
   CODIGO_GIR                     int AUTO_INCREMENT             not null,
   SECCION_GIR                    varchar(100)                   not null,
   primary key (CODIGO_GIR)
);

/*==============================================================*/
/* Table: IMPORTACIONES                                         */
/*==============================================================*/
create table IMPORTACIONES
(
   ORDEN_IMP                      int AUTO_INCREMENT             not null,
   CODIGO_DES                     int                            not null,
   CODIGO_BAN                     int                            not null,
   CODIGO_CAN                     char(1)                        not null,
   CODIGO_GIR                     int,
   CODIGO_CLI                     int                            not null,
   CODIGO_CAR                     int                            not null,
   CODIGO_TRA                     int                            not null,
   CODIGO_MON                     char(3)                        not null,
   CODIGO_OPP                     int,
   REFERENCIA_IMP                 varchar(150)                   not null,
   FECHAINGRESO_IMP               date                           not null,
   ORIGINALOCOPIA_IMP             char(1)                        not null,
   DESCMERCADERIA_IMP             varchar(150)                   not null,
   VALORFACTURA_IMP               float                          not null,
   DOCTRANSPORTE_IMP              varchar(30)                    not null,
   INGRESOPUERTO_IMP              date,
   DES_NRODOC                     varchar(40)                    not null,
   DES_VENCIMIENTO                date,
   DES_B_L                        varchar(50),
   DES_DECLARACION                varchar(10)                    not null,
   DES_PRESENTADO                 date                           not null,
   DES_SALIDO                     date                           not null,
   DES_CARGADO                    date                           not null,
   DES_FACTURA                    varchar(50),
   DES_FECHAFACTURA               date,
   primary key (ORDEN_IMP)
);

/*==============================================================*/
/* Table: MEDIOS                                                */
/*==============================================================*/
create table MEDIOS
(
   CODIGOMED                      int AUTO_INCREMENT             not null,
   DESCRIPCION_MED                varchar(50)                    not null,
   primary key (CODIGOMED)
);

/*==============================================================*/
/* Table: MONEDAS                                               */
/*==============================================================*/
create table MONEDAS
(
   CODIGO_MON                     char(3)                        not null,
   DESCRIPCION_MON                varchar(150),
   primary key (CODIGO_MON)
);

/*==============================================================*/
/* Table: OPP                                                   */
/*==============================================================*/
create table OPP
(
   CODIGO_OPP                     int AUTO_INCREMENT             not null,
   ORDEN_IMP                      int                            not null,
   DECLARACION_OK_OPP             char(1)                        not null,
   PEDIDO_DE_DINERO_OPP           date                           not null,
   OTROS_OPP                      varchar(255),
   FRACCIONADO_OPP                varchar(150),
   ESTAMPILLAS_OPP                varchar(150),
   IMPUESTOS_INTERNOS_OPP         varchar(150),
   primary key (CODIGO_OPP)
);

/*==============================================================*/
/* Table: PASA_POR                                              */
/*==============================================================*/
create table PASA_POR
(
   CODIGO_BUQ                     int                            not null,
   CODIGO_PUE                     int                            not null,
   FECHA_PAS                      date,
   primary key (CODIGO_BUQ, CODIGO_PUE)
);

/*==============================================================*/
/* Table: PROVEEDOR_MEDIO                                       */
/*==============================================================*/
create table PROVEEDOR_MEDIO
(
   CODIGO_TRA                     int AUTO_INCREMENT             not null,
   NOMBRE_TRA                     varchar(100)                   not null,
   DIRECCION_TRA                  varchar(200)                   not null,
   TELEFONOS_TRA                  varchar(150)                   not null,
   FAX_TRA                        varchar(30),
   MAIL_TRA                       varchar(100),
   primary key (CODIGO_TRA)
);

/*==============================================================*/
/* Table: PUERTOS                                               */
/*==============================================================*/
create table PUERTOS
(
   CODIGO_PUE                     int AUTO_INCREMENT             not null,
   NOMBRE_PUE                     varchar(200)                   not null,
   UBICACION_PUE                  varchar(255),
   primary key (CODIGO_PUE)
);

/*==============================================================*/
/* Table: TABLAS                                                */
/*==============================================================*/
create table TABLAS
(
   NOMBRE                         varchar(50)                    not null,
   FECHA                          date,
   primary key (NOMBRE)
);

/*==============================================================*/
/* Table: TRANSPORTES                                           */
/*==============================================================*/
create table TRANSPORTES
(
   CODIGO_BUQ                     int AUTO_INCREMENT             not null,
   CODIGO_BAN                     int                            not null,
   CODIGOMED                      int                            not null,
   NOMBRE_BUQ                     varchar(100)                   not null,
   OBSERVACIONES_BUQ              text,
   primary key (CODIGO_BUQ)
);

/*==============================================================*/
/* Table: USUARIOS                                              */
/*==============================================================*/
create table USUARIOS
(
   CODIGO_USU                     int AUTO_INCREMENT             not null,
   NOMBRE_USU                     varchar(50),
   PASSWORD_USU                   varbinary(128),
   USUARIO_USU                    varchar(30),
   IDIOMA_USU                     int,
   primary key (CODIGO_USU)
);

/*==============================================================*/
/* Table: IDIOMAS                                               */
/*==============================================================*/
create table IDIOMAS
(
   CODIGO_IDI                     int                            not null,
   NOMBRE_IDI                     varchar(50)                    not null,
   primary key (CODIGO_IDI)
);

/*==============================================================*/
/* Table: USUARIO_MODIFICA_TABLA                                */
/*==============================================================*/
create table USUARIO_MODIFICA_TABLA
(
   CODIGO_USU                     int AUTO_INCREMENT             not null,
   NOMBRE                         varchar(50)                    not null,
   primary key (CODIGO_USU, NOMBRE)
);

