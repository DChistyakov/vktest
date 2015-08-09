var filters = angular.module('VkTestApp.filters', []);

filters.filter('startFrom', function (){
   return function (input, start){
      if(!input){
         return [];
      }
      start = +start;
      return input.slice(start);
   }
});

filters.filter('date_rus', function (){
   return function (date, ignoreTime){
      if(!date){
         return '';
      }

      if((date + '').indexOf('-') != -1){
         if((date + '').indexOf(':') != -1){
            var mom = moment(date, 'YYYY-MM-DD HH:mm:ss');
         } else{
            var mom = moment(date, 'YYYY-MM-DD');
         }
      } else{
         var mom = moment.unix(date);
      }
      if(ignoreTime){
         return mom.format('DD.MM.YYYY');
      } else{
         return mom.format('DD.MM.YYYY HH:mm');
      }
   }
});

filters.filter('fromNow', function (){
   return function (datetime){
      var mom = moment(datetime, 'YYYY-MM-DD HH:mm:ss');
      return mom.fromNow();
   }
});

filters.filter('number_format', function ($filter){
   return function (input, decPlaces, thouSep, decSep){
      var decPlaces = decPlaces || 0;
      var thouSep = thouSep || ",";
      var decSep = decSep || ".";

      // Check for invalid inputs
      if ( typeof(input) == 'undefined' ) {
         input = 0;
      }

      var regexp = /^([0-9\-])/im;
      if ( !regexp.test(input) ) {
         return input;
      }


      var out = isNaN(input) || input === '' || input === null ? 0.0 : input;

      //Deal with the minus (negative numbers)
      var minus = input < 0;
      out = Math.abs(out);
      out = $filter('number')(out, decPlaces);

      // Replace the thousand and decimal separators.
      // This is a two step process to avoid overlaps between the two
      if(thouSep != ",") out = out.replace(/\,/g, "T");
      if(decSep != ".") out = out.replace(/\./g, "D");
      out = out.replace(/T/g, thouSep);
      out = out.replace(/D/g, decSep);

      // Add the minus and the symbol
      if(minus){
         return "-" + out;
      } else{
         return out;
      }
   }
});