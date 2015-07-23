(function($){
    $.Tree = {version : '0.1.3'};

    $.fn.Tree = function(settings)
    {
        settings = jQuery.extend({ ajax	: false }, settings);

        /**
         * Обработчик AJAX запросов
         * получает первую линию активного узла
         * вставляет HTML после активного узла
         * @param options {Object}
         */
        send_ajax = function (options) {

            if (options.node_attributes != undefined)
            {
                $.each(options.node_attributes, function(index, v){
                    var value = $.Tree.node.attr(v);

                    if (value != undefined){
                        if (options.data == undefined) options.data = {};
                        options.data[index] = value;
                    }
                });
            }

            options = jQuery.extend({
                url		: false,
                data	: {},
                onError	: function (){
                    $.Tree.show_loading(false);
                    alert('Ошибка запроса');
                },
                cache		: false,
                type 		: 'POST',
                async		: false,
                dataType	: 'json'
            }, options);

            if ( !options.url ) return false;

            $.Tree.show_loading(true);

            $.ajax({
                url			: options.url,
                type		: options.type,
                dataType	: options.dataType,
                error		: options.onError,
                success		: function(data){
                    if (data.error){
                        alert(data.error);
                    }

                    if (data.content){
                        $.Tree.node.after(data.content);
                        $.Tree.toggle_node_state();
                        //T.branch_manager('expand');
                        $.Tree.show_loading(false);
                    }
                },
                data		: options.data,
                async		: options.async,
                cache		: options.cache
            });
        };


        /**
         * Обработчик события 'click'
         * @param event {Object}
         */
        toggle = function (event) {
            var node = $(event.target).parents('tr:first');

            if ( $.Tree.toggle(node) ) return false;


            if (!settings.ajax || typeof(settings.ajax) != 'object') return false;

            send_ajax(settings.ajax);

            return false;
        };


        this.each(function(){

            $('#nav_tree.list tr:visible:odd').addClass('odd');
            $('#nav_tree.list tr:visible:even').addClass('even');
            $(this).find('.row-container.expanded .tc-element, .row-container.collapsed .tc-element').on('click', toggle);

            return false; // вешаем обработчик только на один (первый) объект-дерево
        });


        /**
         * Управляет разворачиванием / сворачиванием первой линии узлов
         * @param action {String}
         * Действие { 'expand' : 'раскрыть' , 'collapse' : 'свернуть' }
         */
        $.Tree.branch_manager = function (action)
        {
            var _this		= this;
            var children	= this.get_first_line();
            var display		= (action == 'expand') ? 'table-row' : 'none';

            children.each(function(){
                var node = $(this);
                node.css('display', display);

                if (node.hasClass('expanded')){
                    _this.node = node;
                    _this.branch_manager(action);
                }

                $('#nav_tree.list tr').removeClass('odd');
                $('#nav_tree.list tr').removeClass('even');
                $('#nav_tree.list tr:visible:odd').addClass('odd');
                $('#nav_tree.list tr:visible:even').addClass('even');
            });
        };

        /**
         * Получение первой линии
         */
        $.Tree.get_first_line = function ()
        {
            return this.node.siblings('tr[parent_row=' + this.get_node_id() + ']');
        };

        /**
         * Получение уникального номера узла (атрибут row)
         */
        $.Tree.get_node_id = function ()
        {
            return this.node.attr('row');
        };

        /**
         * Управляет состоянием активного элемента
         * состояние { 'tc-element-loading' : 'подгружаются узлы' , 'tc-element' : 'обычное состояние' }
         * @param on {Boolean}
         */
        $.Tree.show_loading = function (on)
        {
            this.node.children('td:first').children('div:first').attr('class', (on ? 'tc-element-loading' : 'tc-element'));
        };

        /**
         * Переключатель процесса узла
         * @param node {jObject}
         */
        $.Tree.toggle = function(node)
        {
            this.set_node(node);

            var first_line = this.get_first_line();

            if ( first_line.length == 0 ) return false;

            this.node.hasClass('expanded') ? this.branch_manager('collapse') : this.branch_manager('expand');

            this.set_node(node);
            this.toggle_node_state();

            return true;
        };

        /**
         * Переключатель состояния узла
         * состояние = { 'expanded' : 'развернуто' , 'collapsed' : 'свернуто' }
         */
        $.Tree.toggle_node_state = function ()
        {
            var node		= this.node;
            var newClass	= node.hasClass('expanded') ? 'collapsed' : 'expanded';
            var regExp		=  /(^|\s)(expanded|collapsed)(\s|$)/;

            node.attr('class', node.attr('class').replace(regExp, '$1' + newClass + '$3'));
        };

        /**
         * Инициализация активного узла
         * @param node {jObject}
         */
        $.Tree.set_node = function(node)
        {
            this.node = node;
        };

        return this;
    };
})(jQuery);