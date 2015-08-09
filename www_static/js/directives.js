var directives = angular.module('VkTestApp.directives', []);

/**
 * Пример:
 <div block-while="dataIsLoading" block-message="Жди! Загружу!">
 or <div block-while="dataIsLoading" block-intable=true block-message="Жди! Загружу!">

 $scope.dataIsLoading = true;
 ProjectsLoader().then(function (response){
    ....
    $scope.dataIsLoading = false;
 });
 */
directives.directive('blockWhile', function (){
   return {
      scope: {
         'blockWhile': '='
      },
      restrict: 'A',
      link: function (scope, element, attrs){
         var intable = (typeof(attrs['blockIntable']) !== 'undefined') ? true : false;
         var message = (typeof(attrs['blockMessage']) !== 'undefined') ? attrs['blockMessage'] : 'Загружается';
         message = message || ' ';

         scope.$watch('blockWhile', function (block){
            if(block){
               Metronic.blockUI({
                  //boxed: true,
                  intable: intable,
                  message: message,
                  target: element,
                  animate: true // для красивой синей штуки
               });
            } else{
               Metronic.unblockUI(element);
            }
         });

      }
   }
});


// Route State Load Spinner(used on page or content load)
directives.directive('ngSpinnerBar', ['$rootScope',
   function ($rootScope){
      return {
         link: function (scope, element, attrs){
            // by defult hide the spinner bar
            element.addClass('hide'); // hide spinner bar by default

            // display the spinner bar whenever the route changes(the content part started loading)
            $rootScope.$on('$stateChangeStart', function (){
               element.removeClass('hide'); // show spinner bar
            });

            // hide the spinner bar on rounte change success(after the content loaded)
            $rootScope.$on('$stateChangeSuccess', function (){
               element.addClass('hide'); // hide spinner bar
               $('body').removeClass('page-on-load'); // remove page loading indicator
            });

            // handle errors
            $rootScope.$on('$stateNotFound', function (){
               element.addClass('hide'); // hide spinner bar
            });

            // handle errors
            $rootScope.$on('$stateChangeError', function (){
               element.addClass('hide'); // hide spinner bar
            });
         }
      };
   }
])

// Handle global LINK click
directives.directive('a', function (){
   return {
      restrict: 'E',
      link: function (scope, elem, attrs){
         if(attrs.ngClick || attrs.href === '' || attrs.href === '#'){
            elem.on('click', function (e){
               e.preventDefault(); // prevent link click for above criteria
            });
         }
      }
   };
});

// Handle Dropdown Hover Plugin Integration
directives.directive('dropdownMenuHover', function (){
   return {
      link: function (scope, elem){
         elem.dropdownHover();
      }
   };
});

/**
 * @deprecated Для обратной совместимости. Нужно удалить и использовать uniform
 */
directives.directive('uniCheckbox', function ($timeout){
   return {
      restrict: 'A',
      require: 'ngModel',
      link: function (scope, element, attr, ngModel){
         element.uniform({useID: false});

         scope.$watch(function (){
            return ngModel.$modelValue
         }, function (){
            $timeout(jQuery.uniform.update, 0);
         });
      }
   };
});

directives.directive('uniform', function ($timeout){
   return {
      restrict: 'A',
      require: 'ngModel',
      link: function (scope, element, attr, ngModel){
         element.uniform({useID: false});

         scope.$watch(function (){
            return ngModel.$modelValue
         }, function (){
            $timeout(jQuery.uniform.update, 0);
         });
      }
   };
});
