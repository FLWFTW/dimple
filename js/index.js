"use strict";

function markup() { 
   var con = document.getElementsByClassName( "dimple-content-markdown" );
   for( var i = 0; i < con.length; i++ )
   {
      let c = con[i];
      // Below code with tokens and such from https://github.com/markedjs/marked/issues/160
      let tokens = marked.lexer( c.innerHTML );
      tokens.forEach( function( token ) { if( token.type === 'code' ) token.escaped = true; } );
      c.innerHTML = marked.parser( tokens );

   };
}
