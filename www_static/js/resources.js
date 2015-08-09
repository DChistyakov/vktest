var resources = angular.module('VkTestApp.resources', []);

resources.factory('VkTestResource', function ($resource, $cookies){
   return function (url, data, additionalMethods, service){
      additionalMethods = additionalMethods || {};
      additionalMethods = angular.extend({
         'query': {method: 'GET', isArray: false},
         'create': {method: 'POST'},
         'save': {method: 'PUT'},
         'executeAction': {
            url: '///dynamic.vk.dchistyakov.ru/index.php?module=' + service + '&op=:action&session_bid=' + $cookies.get('session_bid') + '&session_key=' + $cookies.get('session_key'),
            method: 'POST',
            params: {action: '@action'}
         }
      }, additionalMethods);
      return $resource(url, data, additionalMethods);
   };
});

resources.factory('VkTestResourcesLoaderGenerator', function ($q){
   return function (resource, noWrap){
      return function (additionalParams){
         var delay = $q.defer();

         var params = {};
         if(typeof(additionalParams) !== 'undefined'){
            params = $.extend(params, additionalParams);
         }

         resource.query(params, function (ajax){
            var items = [];
            if(!noWrap){
               if(angular.isArray(ajax.data)){
                  angular.forEach(ajax.data, function (item){
                     if(typeof item === "object"){
                        items.push(new resource(item));
                     }
                  });
               }
            } else{
               items = ajax.data;
            }
            items._meta = ajax.meta;
            delay.resolve(items);
         }, function (){
            delay.reject('Fail load');
         });
         return delay.promise;
      }
   };
});

resources.factory('VkTestOneResourceLoaderGenerator', function ($q){
   return function (resource){
      return function (additionalParams){
         var delay = $q.defer();

         var params = {};
         if(typeof(additionalParams) !== 'undefined'){
            params = $.extend(params, additionalParams);
         }

         resource.get(params, function (item){
            if(typeof(item.items) !== 'undefined'
                  && typeof(item._meta) !== 'undefined'
            ){
               if(item.items.length && item.items[0] && typeof(item.items[0]) == 'object'){
                  delay.resolve(new resource(item.items[0]));
               } else{
                  delay.reject('Не найден');
               }
            } else{
               delay.resolve(item);
            }
         }, function (){
            delay.reject('Fail load');
         });
         return delay.promise;
      }
   }
});


resources.factory('Order', function (VkTestResource, $cookies){
   return VkTestResource('///dynamic.vk.dchistyakov.ru/index.php?module=customer&op=getOrders&session_bid=' + $cookies.get('session_bid') + '&session_key=' + $cookies.get('session_key') + '&id=:id', {id: '@id'}, {}, 'customer');
});

resources.factory('OrdersLoader', function (VkTestResourcesLoaderGenerator, Order){
   return VkTestResourcesLoaderGenerator(Order);
});

resources.factory('Info', function (VkTestResource, $cookies){
   return VkTestResource('//dynamic.vk.dchistyakov.ru/index.php?module=common&op=getInfo&session_bid=' + $cookies.get('session_bid') + '&session_key=' + $cookies.get('session_key'), {}, {}, 'common-info');
});

resources.factory('CommonInfoLoader', function (VkTestOneResourceLoaderGenerator, Info){
   return VkTestOneResourceLoaderGenerator(Info);
});