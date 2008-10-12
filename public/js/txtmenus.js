// JavaScript Document

var txtmenuadminspa =
[
    { text: "Logout", helptext: "" ,
        onclick: {fn: onMenuItemClick,
        obj: { id: 'logout', url:"/admin/panel/logout"} } },

    {text: "Usuarios",
        submenu:
        {
            id: "usuariosadminmenu",
            itemdata:
            [
                [
                { text: "Agregar usuario", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'agregarusuario', url:"/admin/users/addusers" }  }},
                { text: "Modificar usuario", helptext: "", onclick: { fn: onMenuItemClick, obj: { id: 'modificarusuario', url:"/admin/users/listusers" }   }},
                { text: "Borrar usuario", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'borrarusuario', url:"/admin/users/listusers" }   }},
                ],
                [
                    { text: "Listado de Usuarios", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'listarusuario', url: "/admin/users/listusers"} }}
                ]
            ]
        }
    },

    { text: "Ultimas Modificaciones", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'listarlogs', url:"/admin/log/listlogs"}  }}
];


var txtmenuspa =
[
    { text: "Logout", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'logout', url: "/user/main-page/logout" } } },
    {text: "Exportaciones",
        submenu: {
                    id: "exportacionesmenu",
                    itemdata:
                    [
                        [
                            { text: "Agregar exportacion", helptext: "" , onclick: { fn: onMenuItemClick }},

                            { text: "Modificar exportacion", helptext: "", onclick: { fn: onMenuItemClick }},

                            { text: "Borrar exportacion", helptext: "" , onclick: { fn: onMenuItemClick }}
                        ],
                        [
                            { text: "Destinaciones", helptext: "" , onclick: { fn: onMenuItemClick }}
                        ],
                        [
                            { text: "Buscar", helptext: "" , onclick: { fn: onMenuItemClick }}
                        ],
                        [
                            { text: "Estadisticas", helptext: "" , onclick: { fn: onMenuItemClick }}
                        ],
                        [
                            { text: "Listado de Exportaciones", helptext: "" , onclick: { fn: onMenuItemClick }}
                        ]
                    ] 
                }
        },
        {
                        text: "Importaciones", 
                        submenu: {  
                            id: "importacionesmenu", 
                            itemdata: [
                                [
									{ text: "Agregar importacion", helptext: "" , onclick: { fn: onMenuItemClick }},
									{ text: "Modificar importacion", helptext: "", onclick: { fn: onMenuItemClick }},
									{ text: "Borrar importacion", helptext: "" , onclick: { fn: onMenuItemClick }}
								],
                                [
									{ text: "Cargas", helptext: "" , onclick: { fn: onMenuItemClick } },
									{ text: "OPP", helptext: "" , onclick: { fn: onMenuItemClick }},
									{ text: "Canales", helptext: "" , onclick: { fn: onMenuItemClick }}
								],
								[{ text: "Listado de importaciones", helptext: "" , onclick: { fn: onMenuItemClick }}]
                            ] 
                        }
        },
        {
            text: "Clientes",
            submenu:
            {
                id: "clientesmenu",
                itemdata:
                [
                    [
                        { text: "Agregar cliente", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'agregarcliente', url: "/user/clientes/addclientes"} }},
                        { text: "Modificar cliente", helptext: "", onclick: { fn: onMenuItemClick, obj: { id: 'modificarcliente', url: "/user/clientes/listclientes"} }},
                        { text: "Borrar cliente", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'borrarcliente', url: "/user/clientes/listclientes"} }}
                    ],
                    [
                        { text: "Listado de clientes", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'listarclientes', url: "/user/clientes/listclientes"} }}
                    ]
                ] 
            }
        },
        {
                        text: "Proveedores", 
                        submenu: {  
                            id: "proveedormenu", 
                            itemdata: [
                                [
									{ text: "Agregar proveedor", helptext: "" , onclick: { fn: onMenuItemClick }},
									{ text: "Modificar proveedor", helptext: "", onclick: { fn: onMenuItemClick }},
									{ text: "Borrar proveedor", helptext: "" , onclick: { fn: onMenuItemClick }}
								],
                                [{ text: "Listado de proveedor", helptext: "" , onclick: { fn: onMenuItemClick }}]
                            ] 
                        }
                    },
        {
            text: "Giros",
            submenu:
            {
                id: "girosmenu",
                itemdata:
                [
                    [
                        { text: "Agregar giro", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'agregargiro', url: "/user/giros/addgiros"} }},
                        { text: "Modificar giro", helptext: "", onclick: { fn: onMenuItemClick, obj: { id: 'modificargiro', url: "/user/giros/listgiros"} }},
                        { text: "Borrar giro", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'borrargiro', url: "/user/giros/listgiros"} }}
                    ],
                    [
                        { text: "Listado de giros", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'listargiro', url: "/user/giros/listgiros"} }}
                    ]
                ]
            }
        },
        {
            text: "Cargas",
            submenu:
            {
                id: "cargasmenu",
                itemdata:
                [
                    [
                        { text: "Agregar carga", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'agregarcarga', url: "/user/cargas/addcargas"} }},
                        { text: "Modificar carga", helptext: "", onclick: { fn: onMenuItemClick, obj: { id: 'modificarcarga', url: "/user/cargas/listcargas"} }},
                        { text: "Borrar carga", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'borrarcarga', url: "/user/cargas/listcargas"} }}
                    ],
                    [
                        { text: "Listado de cargas", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'listarcarga', url: "/user/cargas/listcargas"} }}
                    ]
                ]
            }
        },
        {
            text: "Puertos",
            submenu:
            {
                id: "puertosmenu",
                itemdata:
                [
                    [
                        { text: "Agregar puerto", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'agregarpuerto', url: "/user/puertos/addpuertos"} }},
                        { text: "Modificar puerto", helptext: "", onclick: { fn: onMenuItemClick, obj: { id: 'modificarpuerto', url: "/user/puertos/listpuertos"} }},
                        { text: "Borrar puerto", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'borrarpuerto', url: "/user/puertos/listpuertos"} }}
                    ],
                    [
                        { text: "Listado de puerto", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'listarpuerto', url: "/user/puertos/listpuertos"} }}]
                ]
            }
        },
        {
            text: "Monedas",
            submenu:
            {
                id: "monedasmenu",
                itemdata:
                [
                    [
                        { text: "Agregar monedas", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'agregarmoneda', url: "/user/monedas/addmonedas"} }},
                        { text: "Modificar monedas", helptext: "", onclick: { fn: onMenuItemClick, obj: { id: 'modificarmoneda', url: "/user/monedas/listmonedas"} }},
                        { text: "Borrar monedas", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'borrarmoneda', url: "/user/monedas/listmonedas"} }}
                    ],
                    [
                        { text: "Listado de monedas", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'listarmoneda', url: "/user/monedas/listmonedas"} }}
                    ]
                ]
            }
        },
        {
            text: "Transportes",
            submenu:
            {
                id: "transportesmenu",
                itemdata:
                [
                    [
                        { text: "Agregar transporte", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'agregartransporte', url: "/user/transportes/addtransportes"} }},
                        { text: "Modificar transporte", helptext: "", onclick: { fn: onMenuItemClick, obj: { id: 'modificartransporte', url: "/user/transportes/listtransportes"} }},
                        { text: "Borrar transporte", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'borrartransportes', url: "/user/transportes/listtransportes"} }}
                    ],
                    [
                        { text: "Listado de transportes", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'listartransporte', url: "/user/transportes/listtransportes"} }}
                    ]
                ]
            }
        },
    {
        text: "Banderas",
        submenu:
        {
            id: "banderasmenu",
            itemdata:
            [
                [
                    { text: "Agregar bandera", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'agregarbandera', url: "/user/banderas/addbanderas"} }},
                    { text: "Modificar bandera", helptext: "", onclick: { fn: onMenuItemClick, obj: { id: 'modificarbandera', url: "/user/banderas/listbanderas"} }},
                    { text: "Borrar bandera", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'borrarbandera', url: "/user/banderas/listbanderas"} }}
                ],
                [
                    { text: "Listado de bandera", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'listarbandera', url: "/user/banderas/listbanderas"} }}
                ]
            ]
        }
    }
];

var txtmenueng = [
                    { 
                        text: "System", 
                        submenu: {  
                            id: "sistemamenu", 
                            itemdata: [
								[
									{ text: "Agregar usuario", helptext: "" },
									{ text: "Ultimas modificaciones", helptext: "", disabled: true }
								],
                                [
									{ text: "Idioma", helptext: "" , disabled: true}
								],
                                [
									{ text: "Salir", helptext: "" }
								]
                            ] 
                        }
                    },
					{ 
                        text: "Exports", 
                        submenu: {  
                            id: "exportacionesmenu", 
                            itemdata: [
                                [
									{ text: "Agregar exportacion", helptext: "" },
									{ text: "Modificar exportacion", helptext: ""},
									{ text: "Borrar exportacion", helptext: "" }
								],
                                [
									{ text: "Cargas", helptext: "" },
									{ text: "Destinaciones", helptext: "" }
								],
								[
									{ text: "Buscar", helptext: "" }
								],
								[
									{ text: "Estadisticas", helptext: "" }
								],
								[{ text: "Listado de clientes", helptext: "" }]
                            ] 
                        }
                    },
					{ 
                        text: "Imports", 
                        submenu: {  
                            id: "importacionesmenu", 
                            itemdata: [
                                [
									{ text: "Agregar importacion", helptext: "" },
									{ text: "Modificar importacion", helptext: ""},
									{ text: "Borrar importacion", helptext: "" }
								],
                                [
									{ text: "Cargas", helptext: "" },
									{ text: "OPP", helptext: "" },
									{ text: "Canales", helptext: "" }
								],
								[{ text: "Listado de importaciones", helptext: "" }]
                            ] 
                        }
                    },
					{ 
                        text: "Clients", 
                        submenu: {  
                            id: "clientesmenu", 
                            itemdata: [
                                [
									{ text: "Agregar cliente", helptext: "" },
									{ text: "Modificar cliente", helptext: ""},
									{ text: "Borrar cliente", helptext: "" }
								],
                                [{ text: "Listado de clientes", helptext: "" }]
                            ] 
                        }
                    },										
					{ 
                        text: "Resellers", 
                        submenu: {  
                            id: "proveedormenu", 
                            itemdata: [
                                [
									{ text: "Agregar proveedor", helptext: "" },
									{ text: "Modificar proveedor", helptext: ""},
									{ text: "Borrar proveedor", helptext: "" }
								],
                                [{ text: "Listado de proveedor", helptext: "" }]
                            ] 
                        }
                    },
					{ 
                        text: "Trans", 
                        submenu: {  
                            id: "girosmenu", 
                            itemdata: [
                                [
									{ text: "Agregar giro", helptext: "" },
									{ text: "Modificar giro", helptext: ""},
									{ text: "Borrar giro", helptext: "" }
								],
                                [{ text: "Listado de giros", helptext: "" }]
                            ] 
                        }
                    },
					{ 
                        text: "Docks", 
                        submenu: {  
                            id: "puertosmenu", 
                            itemdata: [
                                [
									{ text: "Agregar puerto", helptext: "" },
									{ text: "Modificar puerto", helptext: ""},
									{ text: "Borrar puerto", helptext: "" }
								],
                                [{ text: "Listado de puerto", helptext: "" }]
                            ] 
                        }
                    },
					{ 
                        text: "Change", 
                        submenu: {  
                            id: "monedasmenu", 
                            itemdata: [
								[
									{ text: "Agregar monedas", helptext: "" },
									{ text: "Modificar monedas", helptext: ""},
									{ text: "Borrar monedas", helptext: "" }
								],
								[{ text: "Listado de monedas", helptext: "" }]
                            ] 
                        }
                    },
					{ 
                        text: "Transportes", 
                        submenu: {  
                            id: "transportesmenu", 
                            itemdata: [
								[
									{ text: "Agregar transporte", helptext: "" },
									{ text: "Modificar transporte", helptext: ""},
									{ text: "Borrar transporte", helptext: "" }
								],
                                [{ text: "Listado de transporte", helptext: "" }]
                            ] 
                        }
                    },
                    {
                        text: "Flags",
                        submenu:
                        {
                            id: "banderasmenu",
                            itemdata:
                            [
                                [
                                    { text: "Agregar bandera", helptext: "" },
                                    { text: "Modificar bandera", helptext: ""},
                                    { text: "Borrar bandera", helptext: "" }
                                ],
                                [
                                    { text: "Listado de bandera", helptext: "" }
                                ]
                            ]
                        }
                    }
                ];
