(function( factory ) {
    if ( typeof define === "function" && define.amd ) {

        // AMD. Register as an anonymous module.
        define([ "../jquery.ui.datepicker" ], factory );
    } else {

        // Browser globals
        factory( jQuery.datepicker );
    }
}(function( datepicker ) {
    datepicker.regional['es'] = {
        closeText: 'Fermer',
        prevText: 'Anterior',
        nextText: 'Siguiente',
        currentText: 'Aujourd\'hui',
        monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
            'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
        monthNamesShort: ['Ene.', 'Feb.', 'Mar.', 'Abr.', 'May.', 'Jun.',
            'Jul.', 'Ago.', 'Sep.', 'Oct.', 'Nnov.', 'Dic.'],
        dayNames: ['Domingo', 'Lunes', 'Martes', 'Miercoles', 'Jueves', 'Viernes', 'Sabado'],
        dayNamesShort: ['dom.', 'lun.', 'mar.', 'mie.', 'jue.', 'vie.', 'sab.'],
        dayNamesMin: ['D','L','M','M','J','V','S'],
        weekHeader: 'Sem.',
        dateFormat: 'yy-mm-dd',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''};
    datepicker.setDefaults(datepicker.regional['es']);

    return datepicker.regional['fr'];

}));