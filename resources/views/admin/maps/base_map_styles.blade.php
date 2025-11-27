<style type="text/css">
    * {
        font-family: Arial;
    }


    .buida {
        background-color: #FFA200 !important;
    }

    .assistencia-justificada {
        background-color: #FFDB3B !important;
        border: 3px solid palegoldenrod;
    }

    .assistencia-si {
        background-color: #91ab35 !important;
        border: 3px solid palegoldenrod;
    }

    .assistencia-no {
        background-color: #bd4949 !important;
        border: 3px solid palegoldenrod;
    }

    section {
        text-align: center;
        margin: 0;
    }

    .panzoom-parent {
        border: 2px solid #333;
    }

    .panzoom-parent .panzoom {
        border: 2px dashed #666;
        pointer-events: none;
    }

    .buttons {
        margin: 40px 0 0;
    }

    .parada {
        position: absolute;
        background-color: #838376;
        z-index: 99;

    }

    .overlay {
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 3;
    }

    .parada span {
        font-family: Arial;
        font-weight: bold;
        font-size: 18px;
        color: #FFF;
        text-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        display: inline-block;
        left: 50%;
        top: 50%;
        position: absolute;
        transform: translate(-50%, -50%);
        -webkit-transform: translate(-50%, -50%);
    }

    .destacat {
        box-shadow: inset 0 0 0 6px rgba(40, 125, 250, 1);
    }

    #opcions .modal-content {
        border-radius: 8px;
    }

    #opcions .modal-header {
        background: #0a5f90;
        margin-bottom: 5px;
    }

    #opcions h1 {
        margin: 0;
        color: #FFF;
        padding: 10px;
        font-size: 20px;
    }

    #opcions h2 {
        color: #49494d;
        font-size: 18px;
        text-align: left;
        margin: 10px 0 4px 0
    }

    #opcions .btn {
        text-decoration: none;
    }

    .tancar {
        background-image: url('../images/maps/tancar.png');
        background-size: cover;
        background-repeat: no-repeat;
        width: 44px;
        height: 43px;
        display: block;
        position: absolute;
        right: 0;
        top: 0
    }

    .boto {
        background: #efefef;
        /* Old browsers */
        background: -moz-linear-gradient(top, #efefef 0%, #d7d7d8 100%);
        /* FF3.6+ */
        background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #efefef), color-stop(100%, #d7d7d8));
        /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(top, #efefef 0%, #d7d7d8 100%);
        /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top, #efefef 0%, #d7d7d8 100%);
        /* Opera 11.10+ */
        background: -ms-linear-gradient(top, #efefef 0%, #d7d7d8 100%);
        /* IE10+ */
        background: linear-gradient(to bottom, #efefef 0%, #d7d7d8 100%);
        /* W3C */
        -webkit-box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 1);
        -moz-box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 1);
        box-shadow: inset 0px 1px 0px 0px rgba(255, 255, 255, 1);
        display: inline-block;
        width: calc(100% - 20px);
        line-height: 40px;
        margin: 5px 10px;
        border-radius: 3px;
        border: #7e7e7e solid 1px;
        color: #000;
        font-weight: bold;
        font-size: 18px;
        text-decoration: none;
    }

    .toggle {
        border: #e8e8e8 solid 1px;
        border-top-color: #b4b4b4;
        margin-bottom: 10px;
        border-radius: 3px;
        overflow: hidden
    }

    .si {
        width: calc(50% - 6px);
        display: inline-block;
        line-height: 34px;
        margin: 3px;
        border-radius: 3px;
        float: left;
        color: #000;
        font-weight: bold;
        font-size: 16px;
        text-decoration: none
    }

    .si.actiu {
        background-color: #91ab35;
        color: #FFF;
    }

    .no {
        width: calc(50% - 6px);
        display: inline-block;
        line-height: 34px;
        margin: 3px;
        border-radius: 3px;
        float: right;
        color: #000;
        font-weight: bold;
        font-size: 16px;
        text-decoration: none
    }

    .no.actiu {
        background-color: #bd4949;
        color: #FFF;
    }

    .map-styles {
        text-align: center;
    }

    .info-maps {
        width: 600px;
    }
</style>
