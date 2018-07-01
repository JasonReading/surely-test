let angular = require('angular');
let angularResource = require('angular-resource');


angular
    .module('todoApp', [angularResource])
    .factory('Todo', function Todo($resource) {
        return $resource(
            '/api/todo/:id',
            {id: '@id'},
            {'update': {method: 'PATCH'}}
        );
    })
    .component('loaderThing', {
        template: '<span ng-if="$ctrl.loading">LOADING!</span>',
        bindings: {
            loading: '='
        }
    })
    .component('todoItem', {
        template: `
<div ng-dblclick="$ctrl.edit($ctrl.item)">
    <form ng-submit="$ctrl.finishEdit($ctrl.editModel)" ng-if="$ctrl.editing">
        <input type="text" ng-model="$ctrl.editModel.description" required>
        <button type="submit">Save</button>
    </form>
    <div ng-if="!$ctrl.editing">
        <span ng-class="{done: $ctrl.item.completed}">{{$ctrl.item.description }}</span>
        <button ng-click="$ctrl.toggleDone($ctrl.item)">{{$ctrl.item.completed?'↺':'✓'}}</button>  
        <button ng-click="$ctrl.delete($ctrl.item)">Delete </button>  
    </div>
    <loader-thing loading="$ctrl.deleting || $ctrl.toggling || $ctrl.saving"></loader-thing>
</div>`,
        bindings: {
            item: '='
        },
        controller: function ($scope, Todo) {
            let ctrl = this;

            ctrl.error = null;

            ctrl.editing = false;
            ctrl.editModel = {};
            ctrl.edit = (item) => {
                ctrl.editing = true;
                ctrl.editModel = {
                    description: item.description,
                    completed: item.completed
                };
            };
            ctrl.finishEdit = (data) => {
                ctrl.saving = true;
                Todo.update({id: ctrl.item.id, todo: data}).$promise
                    .then(e => {
                        ctrl.item.description = e.description;
                        ctrl.item.completed = e.completed;
                    })
                    .catch(e => ctrl.error = e)
                    .finally(e => ctrl.editing = ctrl.saving = false);
            };

            ctrl.deleting = false;
            ctrl.toggling = false;
            ctrl.saving = false;

            ctrl.delete = (item) => {
                ctrl.deleting = true;
                Todo.delete({id: item.id}).$promise
                    .then(e => $scope.$emit('ITEM_DELETED', item))
                    .catch(e => $scope.$emit('FORM_ERROR', e))
                    .finally(e => ctrl.deleting = false);
            };
            ctrl.toggleDone = (item) => {
                ctrl.toggling = true;
                Todo.update({id: item.id, todo: {completed: !item.completed}}).$promise
                    .then(e => item.completed = e.completed)
                    .catch(e => $scope.$emit('FORM_ERROR', e))
                    .finally(e => ctrl.toggling = false);
            };
        }

    })
    .component('todoApp', {
        template: `
        <h1>Todo:</h1>
        <p>Double click an item to edit</p>
        
        <loader-thing loading="$ctrl.loading"></loader-thing>
        <ul>
            <li ng-repeat="item in $ctrl.list track by item.id">
                <todo-item item="item"></todo-item>
            </li>
        </ul>
        <form ng-submit="$ctrl.addItem($ctrl.newTask)"><input type="text" ng-model="$ctrl.newTask" placeholder="Task" required><button type="submit">+</button></form>
        `,
        controller: function ($scope, $element, $attrs, Todo) {
            var ctrl = this;

            ctrl.list = [];
            ctrl.loading = true;
            ctrl.adding = false;
            ctrl.errors = [];
            ctrl.newTask = '';

            // Load list
            Todo.query().$promise
                .then(d => ctrl.list = d)
                .catch(e => ctrl.errors = e)
                .finally(e => ctrl.loading = false);


            ctrl.addItem = text => {
                ctrl.adding = true;
                Todo.save({todo: {description: text}}).$promise
                    .then(d => {
                        $scope.$emit('ITEM_ADDED', d);
                    })
                    .catch(e => ctrl.errors = e)
                    .finally(e => {
                        ctrl.adding = false;
                        ctrl.newTask = '';
                    });
            };

            $scope.$on('ITEM_DELETED', (e, item) => {
                ctrl.list = ctrl.list.filter(i => i !== item);
            });

            $scope.$on('ITEM_ADDED', (e, item) => {
                ctrl.list.push(item);
            });


        }
    });

angular.bootstrap(document.body);