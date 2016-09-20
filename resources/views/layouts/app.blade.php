<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui.css">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    &nbsp;
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Register</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ url('/logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/1.0.15/vue.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script>
        new Vue({
            el: '.todo-list',
            data: {
                item: '',
                code: '',
                items: [],
            },
            methods: {
                addItem: function (survey, group, question) {
                    var item = {
                        id: Date.now(),
                        code: this.code,
                        answer_text: this.item,
                        temporary: true
                    };

                    this.items.push(item);

                    $.ajax({
                        url: '/admins/surveys/' + survey + '/groups/' + group + '/questions/' + question + '/answers',
                        type: 'post',
                        cache: false,
                        data: {
                            code: this.code,
                            answer_text: this.item
                        }
                    });

                    this.item = '';
                    this.code = '';
                },
                removeItem: function (item) {
                    var newItems = this.items.filter(function (i) {
                        return item.id !== i.id;
                    });

                    this.items = newItems;
                }
            },
            ready: function () {

                // Get URL
                var url = window.location.href

                // Define string paths to match before and after ID
                var path_before = 'questions/'
                var path_after = '/answers'

                // Get string index of start and end of ID
                var pos_before = url.search(path_before) + path_before.length
                var pos_after = url.search(path_after)

                // Get ID from URL
                var question_id = url.substring(pos_before, pos_after)

                $.ajax({
                    url: '/admins/get-all?question_id=' + question_id,
                    type: 'get',
                    cache: false
                }).success(function (data) {
                    this.items = data;
                }.bind(this));
            }
        });
    </script>
</body>
</html>
