// JavaScript Document

var txtmenuadminspa = [
                    { text: "Logout", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'logout', url:"panel/logout"} } },
					{ 
                        text: "Usuarios", 
                        submenu: {  
                            id: "usuariosadminmenu", 
                            itemdata: [
                                [
									{ text: "Agregar usuario", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'agregarusuario', url:"adduser" }  }},
									{ text: "Modificar usuario", helptext: "", onclick: { fn: onMenuItemClick, obj: { id: 'modificarusuario', url:"moduser" }   }},
									{ text: "Borrar usuario", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'borrarusuario', url:"moduser" }   }}
								]
                            ] 
                        }
                    },
					{ text: "Ultimas Modificaciones", helptext: "" , onclick: { fn: onMenuItemClick } }
													
                ];


var txtmenuspa = [
                    { text: "Logout", helptext: "" , onclick: { fn: onMenuItemClick, obj: { id: 'logout', url: "main-page/logout" } } },
					{ 
                        text: "Exportaciones", 
                        submenu: {  
                            id: "exportacionesmenu", 
                            itemdata: [
                                [
									{ text: "Agregar exportacion", helptext: "" , onclick: { fn: onMenuItemClick }},
									{ text: "Modificar exportacion", helptext: "", onclick: { fn: onMenuItemClick }},
									{ text: "Borrar exportacion", helptext: "" , onclick: { fn: onMenuItemClick }}
								],
                                [
									{ text: "Cargas", helptext: "" , onclick: { fn: onMenuItemClick }},
									{ text: "Destinaciones", helptext: "" , onclick: { fn: onMenuItemClick }}
								],
								[
									{ text: "Buscar", helptext: "" , onclick: { fn: onMenuItemClick }}
								],
								[
									{ text: "Estadisticas", helptext: "" , onclick: { fn: onMenuItemClick }}
								],
								[{ text: "Listado de clientes", helptext: "" , onclick: { fn: onMenuItemClick }}]
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
                        submenu: {  
                            id: "clientesmenu", 
                            itemdata: [
                                [
									{ text: "Agregar cliente", helptext: "" , onclick: { fn: onMenuItemClick }},
									{ text: "Modificar cliente", helptext: "", onclick: { fn: onMenuItemClick }},
									{ text: "Borrar cliente", helptext: "" , onclick: { fn: onMenuItemClick }}
								],
                                [{ text: "Listado de clientes", helptext: "" , onclick: { fn: onMenuItemClick }}]
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
                        submenu: {  
                            id: "girosmenu", 
                            itemdata: [
                                [
									{ text: "Agregar giro", helptext: "" , onclick: { fn: onMenuItemClick }},
									{ text: "Modificar giro", helptext: "", onclick: { fn: onMenuItemClick }},
									{ text: "Borrar giro", helptext: "" , onclick: { fn: onMenuItemClick }}
								],
                                [{ text: "Listado de giros", helptext: "" , onclick: { fn: onMenuItemClick }}]
                            ] 
                        }
                    },
					{ 
                        text: "Puertos", 
                        submenu: {  
                            id: "puertosmenu", 
                            itemdata: [
                                [
									{ text: "Agregar puerto", helptext: "" , onclick: { fn: onMenuItemClick }},
									{ text: "Modificar puerto", helptext: "", onclick: { fn: onMenuItemClick }},
									{ text: "Borrar puerto", helptext: "" , onclick: { fn: onMenuItemClick }}
								],
                                [{ text: "Listado de puerto", helptext: "" , onclick: { fn: onMenuItemClick }}]
                            ] 
                        }
                    },
					{ 
                        text: "Monedas", 
                        submenu: {  
                            id: "monedasmenu", 
                            itemdata: [
								[
									{ text: "Agregar monedas", helptext: "" , onclick: { fn: onMenuItemClick }},
									{ text: "Modificar monedas", helptext: "", onclick: { fn: onMenuItemClick }},
									{ text: "Borrar monedas", helptext: "" , onclick: { fn: onMenuItemClick }}
								],
								[{ text: "Listado de monedas", helptext: "" , onclick: { fn: onMenuItemClick }}]
                            ] 
                        }
                    },
					{ 
                        text: "Transportes", 
                        submenu: {  
                            id: "transportesmenu", 
                            itemdata: [
								[
									{ text: "Agregar transporte", helptext: "" , onclick: { fn: onMenuItemClick }},
									{ text: "Modificar transporte", helptext: "", onclick: { fn: onMenuItemClick }},
									{ text: "Borrar transporte", helptext: "" , onclick: { fn: onMenuItemClick }}
								],
                                [{ text: "Listado de transporte", helptext: "" , onclick: { fn: onMenuItemClick }}]
                            ] 
                        }
                    },
					{ 
                        text: "Banderas", 
                        submenu: {  
                            id: "banderasmenu", 
                            itemdata: [
								[
									{ text: "Agregar bandera", helptext: "" , onclick: { fn: onMenuItemClick }},
									{ text: "Modificar bandera", helptext: "", onclick: { fn: onMenuItemClick }},
									{ text: "Borrar bandera", helptext: "" , onclick: { fn: onMenuItemClick }}
								],
                                [{ text: "Listado de bandera", helptext: "" , onclick: { fn: onMenuItemClick }}]
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
                        submenu: {  
                            id: "banderasmenu", 
                            itemdata: [
								[
									{ text: "Agregar bandera", helptext: "" },
									{ text: "Modificar bandera", helptext: ""},
									{ text: "Borrar bandera", helptext: "" }
								],
                                [{ text: "Listado de bandera", helptext: "" }]
                            ] 
                        }
                    }								
                ];
