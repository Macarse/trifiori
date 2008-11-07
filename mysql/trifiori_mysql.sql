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

drop table if exists LOGS;

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

drop table if exists CSS;

/*==============================================================*/
/* Table: BANDERAS                                              */
/*==============================================================*/
create table BANDERAS
(
   CODIGO_BAN                     int AUTO_INCREMENT             not null,
   NOMBRE_BAN                     varchar(150)                   not null,
   primary key (CODIGO_BAN),
   unique NOMBRE_BAN (NOMBRE_BAN)
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
   CODIGO_CAR                     int AUTO_INCREMENT             not null,
   CANTBULTOS_CAR                 int                            not null,
   TIPOENVASE_CAR                 varchar(100)                   not null,
   PESOBRUTO_CAR                  float                          not null,
   UNIDAD_CAR                     varchar(30)                    not null,
   NROPAQUETE_CAR                 varchar(25),
   MARCAYNUMERO                   varchar(100),
   MERC__IMCO                     varchar(100),
   primary key (CODIGO_CAR),
   unique NROPAQUETE_CAR (NROPAQUETE_CAR)
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
   primary key (CODIGO_CLI),
   unique CUIT_CLI (CUIT_CLI)
);

/*==============================================================*/
/* Table: DESTINACIONES                                         */
/*==============================================================*/
create table DESTINACIONES
(
   CODIGO_DES                     int AUTO_INCREMENT              not null,
   DESCRIPCION_DES                varchar(150),
   primary key (CODIGO_DES),
   unique DESCRIPCION_DES (DESCRIPCION_DES)
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
   CODIGO_EXP                     int AUTO_INCREMENT             not null,
   ORDEN                          int                            not null,
   CODIGO_TRA                     int                            not null,
   CODIGO_CLI                     int                            not null,
   CODIGO_BAN                     int                            not null,
   CODIGO_MON                     int                            not null,
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
   primary key (CODIGO_EXP),
   UNIQUE ORDEN (ORDEN)
);

/*==============================================================*/
/* Table: GIROS                                                 */
/*==============================================================*/
create table GIROS
(
   CODIGO_GIR                     int AUTO_INCREMENT             not null,
   SECCION_GIR                    varchar(100)                   not null,
   primary key (CODIGO_GIR),
   unique SECCION_GIR (SECCION_GIR)
);

/*==============================================================*/
/* Table: IMPORTACIONES                                         */
/*==============================================================*/
create table IMPORTACIONES
(
   CODIGO_IMP                     int AUTO_INCREMENT             not null,
   ORDEN_IMP                      int                            not null,
   CODIGO_DES                     int                            not null,
   CODIGO_BAN                     int                            not null,
   CODIGO_CAN                     char(1)                        not null,
   CODIGO_GIR                     int,
   CODIGO_CLI                     int                            not null,
   CODIGO_CAR                     int                            not null,
   CODIGO_TRA                     int                            not null,
   CODIGO_MON                     int                            not null,
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
   primary key (CODIGO_IMP),
   UNIQUE ORDEN_IMP (ORDEN_IMP)
);

/*==============================================================*/
/* Table: LOGS                                                  */
/*==============================================================*/
create table LOGS
(
   CODIGOLOG                      int AUTO_INCREMENT             not null,
   NIVEL                          int                            not null,
   MSG                            varchar(250)                   not null,
   primary key (CODIGOLOG)
);

/*==============================================================*/
/* Table: MEDIOS                                                */
/*==============================================================*/
create table MEDIOS
(
   CODIGOMED                      int AUTO_INCREMENT             not null,
   DESCRIPCION_MED                varchar(50)                    not null,
   primary key (CODIGOMED),
   UNIQUE DESCRIPCION_MED (DESCRIPCION_MED)
);

/*==============================================================*/
/* Table: MONEDAS                                               */
/*==============================================================*/
create table MONEDAS
(
   CODIGO_MON                     int AUTO_INCREMENT             not null,
   NAME_MON                       char(3)                        not null,
   DESCRIPCION_MON                varchar(150),
   primary key (CODIGO_MON),
   UNIQUE NAME_MON (NAME_MON)
);

/*==============================================================*/
/* Table: OPP                                                   */
/*==============================================================*/
create table OPP
(
   CODIGO_OPP                     int AUTO_INCREMENT             not null,
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
   FAX_TRA                        varchar(150),
   MAIL_TRA                       varchar(100),
   primary key (CODIGO_TRA),
   UNIQUE NOMBRE_TRA (NOMBRE_TRA)
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
   primary key (CODIGO_BUQ),
   UNIQUE NOMBRE_BUQ (NOMBRE_BUQ)
);

/*==============================================================*/
/* Table: CSS                                                   */
/*==============================================================*/
create table CSS
(
   CODIGO_CSS                     int AUTO_INCREMENT             not null,
   NOMBRE_CSS                     varchar(100)                   not null,
   primary key (CODIGO_CSS),
   unique NOMBRE_CSS (NOMBRE_CSS)
);

/*==============================================================*/
/* Table: USUARIOS                                              */
/*==============================================================*/
create table USUARIOS
(
   CODIGO_USU                     int AUTO_INCREMENT             not null,
   NOMBRE_USU                     varchar(50)                    not null,
   PASSWORD_USU                   varbinary(128),
   USUARIO_USU                    varchar(30),
   IDIOMA_USU                     int,
   CODIGO_CSS                     int,
   primary key (CODIGO_USU),
   unique USUARIO_USU (USUARIO_USU)
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

alter table ES_CONTRATADO_POR
   add constraint FK_ES_CONTR_ES_CONTRA_TRANSPOR foreign key (CODIGO_BUQ)
      references TRANSPORTES (CODIGO_BUQ);

alter table ES_CONTRATADO_POR
   add constraint FK_ES_CONTR_ES_CONTRA_PROVEEDO foreign key (CODIGO_TRA)
      references PROVEEDOR_MEDIO (CODIGO_TRA);

alter table USUARIOS
   add constraint FK_USUARIOS_CSS foreign key (CODIGO_CSS)
      references CSS (CODIGO_CSS);

alter table EXPORTACIONES
   add constraint FK_EXPORTAC_EXPORTA_CARGAS foreign key (CODIGO_CAR)
      references CARGAS (CODIGO_CAR);

alter table EXPORTACIONES
   add constraint FK_EXPORTAC_EXP_TIENE_DESTINAC foreign key (CODIGO_DES)
      references DESTINACIONES (CODIGO_DES);

alter table EXPORTACIONES
   add constraint FK_EXPORTAC_REALIZA_CLIENTES foreign key (CODIGO_CLI)
      references CLIENTES (CODIGO_CLI);

alter table EXPORTACIONES
   add constraint FK_EXPORTAC_SE_ALMACE_GIROS foreign key (CODIGO_GIR)
      references GIROS (CODIGO_GIR);

alter table EXPORTACIONES
   add constraint FK_EXPORTAC_TIENE_PRO_PROVEEDO foreign key (CODIGO_TRA)
      references PROVEEDOR_MEDIO (CODIGO_TRA);

alter table EXPORTACIONES
   add constraint FK_EXPORTAC_UTILIZA_MONEDAS foreign key (CODIGO_MON)
      references MONEDAS (CODIGO_MON);

alter table EXPORTACIONES
   add constraint FK_EXPORTAC_VA_HACIA_BANDERAS foreign key (CODIGO_BAN)
      references BANDERAS (CODIGO_BAN);

alter table IMPORTACIONES
   add constraint FK_IMPORTAC_ES_UN_IMP_DESTINAC foreign key (CODIGO_DES)
      references DESTINACIONES (CODIGO_DES);

alter table IMPORTACIONES
   add constraint FK_IMPORTAC_IMPORTA_CARGAS foreign key (CODIGO_CAR)
      references CARGAS (CODIGO_CAR);

alter table IMPORTACIONES
   add constraint FK_IMPORTAC_REALIZA_I_CLIENTES foreign key (CODIGO_CLI)
      references CLIENTES (CODIGO_CLI);

alter table IMPORTACIONES
   add constraint FK_IMPORTAC_SE_ALMACE_GIROS foreign key (CODIGO_GIR)
      references GIROS (CODIGO_GIR);

alter table IMPORTACIONES
   add constraint FK_IMPORTAC_TIENE_OPP foreign key (CODIGO_OPP)
      references OPP (CODIGO_OPP);

alter table IMPORTACIONES
   add constraint FK_IMPORTAC_TIENE_PRO_PROVEEDO foreign key (CODIGO_TRA)
      references PROVEEDOR_MEDIO (CODIGO_TRA);

alter table IMPORTACIONES
   add constraint FK_IMPORTAC_TIENE_VER_CANALES foreign key (CODIGO_CAN)
      references CANALES (CODIGO_CAN);

alter table IMPORTACIONES
   add constraint FK_IMPORTAC_UTILIZA_I_MONEDAS foreign key (CODIGO_MON)
      references MONEDAS (CODIGO_MON);

alter table IMPORTACIONES
   add constraint FK_IMPORTAC_VIENE_DE_BANDERAS foreign key (CODIGO_BAN)
      references BANDERAS (CODIGO_BAN);

alter table PASA_POR
   add constraint FK_PASA_POR_PASA_POR_TRANSPOR foreign key (CODIGO_BUQ)
      references TRANSPORTES (CODIGO_BUQ);

alter table PASA_POR
   add constraint FK_PASA_POR_PASA_POR2_PUERTOS foreign key (CODIGO_PUE)
      references PUERTOS (CODIGO_PUE);

alter table TRANSPORTES
   add constraint FK_TRANSPOR_SE_ENCUEN_BANDERAS foreign key (CODIGO_BAN)
      references BANDERAS (CODIGO_BAN);

alter table TRANSPORTES
   add constraint FK_TRANSPOR_UTILIZA_M_MEDIOS foreign key (CODIGOMED)
      references MEDIOS (CODIGOMED);

alter table USUARIO_MODIFICA_TABLA
   add constraint FK_USUARIO__USUARIO_M_TABLAS foreign key (NOMBRE)
      references TABLAS (NOMBRE);
