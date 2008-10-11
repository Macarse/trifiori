// JavaScript Document

function onMenuItemClick(p_sType, p_aArgs, p_Data) 
{
    /*
    Handlers del menu
            Index = this.index 
            Text = this.cfg.getProperty("text")
            Value = p_oValue
    */

    switch(p_Data.id)
    {
        case "agregarusuario":
        case "borrarusuario":
        case "modificarusuario":
        case "logout":
        case "agregarbandera":
        case "modificarbandera":
        case "borrarbandera":
        case "listbandera":
            window.location = p_Data.url;
            break;
    }
    //alert(p_Data.id + ' ' + p_Data.url);
} 
