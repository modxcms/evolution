/* -- 
 * Author: Alex Cruikshank
 * 07.27.2006
 * License
 * Copyright (c) 2006 CNET Networks, Inc.

 * Permission is hereby granted, free of charge, to any person obtaining a copy of 
 * this software and associated documentation files (the "Software"), to deal in the
 * Software without restriction, including without limitation the rights to use, copy, 
 * modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, 
 * and to permit persons to whom the Software is furnished to do so, subject to the 
 * following conditions:

 * The above copyright notice and this permission notice shall be included in all copies
 * or substantial portions of the Software.

 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, 
 * INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR 
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE 
 * FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR 
 * OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER 
 * DEALINGS IN THE SOFTWARE. -- */

Prototypify = function() {

}

Prototypify.prototypified = false;

// store then remove functions from prototypes of native objects
Prototypify.arrayFunctionHolder = new Object()
for (x in Array.prototype)
{
  Prototypify.arrayFunctionHolder[x] = Array.prototype[x];
  delete Array.prototype[x];
}

Prototypify.stringFunctionHolder = new Object()
for (x in String.prototype)
{
  Prototypify.stringFunctionHolder[x] = String.prototype[x];
  delete String.prototype[x];
}

Prototypify.numberFunctionHolder = new Object()
for (x in Number.prototype)
{
  Prototypify.numberFunctionHolder[x] = Number.prototype[x];
  delete Number.prototype[x];
}


Prototypify.proxy = function( f, proxyArguments )
{
  return function()
    {
      var needsPrototypes = ! Prototypify.prototypified;
      if ( needsPrototypes )
      {
        Prototypify.prototypified = true;
        for (x in Prototypify.arrayFunctionHolder)
          Array.prototype[x] = Prototypify.arrayFunctionHolder[x];
        for (x in Prototypify.stringFunctionHolder)
          String.prototype[x] = Prototypify.stringFunctionHolder[x];
        for (x in Prototypify.numberFunctionHolder)
          Number.prototype[x] = Prototypify.numberFunctionHolder[x];
      }

      if ( proxyArguments )
      {
        for ( var i=0; i < arguments.length; i++ )
          if ( typeof arguments[i] == 'function' )
            arguments[i] = Prototypify.proxy( arguments[i], proxyArguments );
      }

      var out = f.apply( this, arguments );

      if ( needsPrototypes )
      {
        for ( x in Array.prototype )
          delete Array.prototype[x];
        for ( x in String.prototype )
          delete String.prototype[x];
        for ( x in Number.prototype )
          delete Number.prototype[x];
        Prototypify.prototypified = false;
      }
      return out
    }
}

Prototypify.instrument = function( clazz, proxyArguments )
{
  for ( prop in clazz.prototype )
  {
    if ( typeof clazz.prototype[prop] == 'function' )
      clazz.prototype[ prop ] = Prototypify.proxy( clazz.prototype[ prop ], proxyArguments );
  }
}

Prototypify.instrumentStatic = function( clazz, proxyArguments )
{
  for ( prop in clazz )
  {
    if ( typeof clazz[prop] == 'function' )
      clazz[ prop ] = Prototypify.proxy( clazz[ prop ], proxyArguments );
  }
}

