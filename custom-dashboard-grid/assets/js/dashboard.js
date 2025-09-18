(function ( $ ) {
    'use strict';

    $( function () {
        var gridElement = $( '.grid-stack' );
        if ( ! gridElement.length || typeof GridStack === 'undefined' ) {
            return;
        }

        var settings = window.CustomDashboardGrid || {};
        var grid      = GridStack.init( {
            column: 4,
            cellHeight: 200,
            margin: 10,
            float: false,
            disableOneColumnMode: true,
            draggable: {
                handle: '.grid-stack-item-content h2'
            },
            resizable: {
                handles: 'e, se, s, sw, w'
            }
        }, '.grid-stack' );

        var savedLayout = Array.isArray( settings.layout ) ? settings.layout : [];

        if ( savedLayout.length ) {
            grid.batchUpdate();
            savedLayout.forEach( function ( item ) {
                var element = gridElement.find( '.grid-stack-item[gs-id="' + item.id + '"]' ).get( 0 );
                if ( element && element.gridstackNode ) {
                    grid.update( element, {
                        x: parseInt( item.x, 10 ) || 0,
                        y: parseInt( item.y, 10 ) || 0,
                        w: parseInt( item.width, 10 ) || 1,
                        h: parseInt( item.height, 10 ) || 1
                    } );
                }
            } );
            grid.commit();
        }

        var saveTimer = null;

        function scheduleSave() {
            if ( saveTimer ) {
                clearTimeout( saveTimer );
            }

            saveTimer = setTimeout( saveLayout, 500 );
        }

        function saveLayout() {
            if ( ! settings.ajaxUrl ) {
                return;
            }

            var layout = [];

            grid.engine.nodes.forEach( function ( node ) {
                if ( ! node.el ) {
                    return;
                }

                var id = node.el.getAttribute( 'gs-id' );
                if ( ! id ) {
                    return;
                }

                layout.push( {
                    id: id,
                    x: node.x,
                    y: node.y,
                    width: node.w,
                    height: node.h
                } );
            } );

            $.ajax( {
                method: 'POST',
                url: settings.ajaxUrl,
                data: {
                    action: 'save_dashboard_layout',
                    nonce: settings.nonce,
                    layout: JSON.stringify( layout )
                }
            } );
        }

        grid.on( 'change', scheduleSave );
    } );
})( jQuery );
