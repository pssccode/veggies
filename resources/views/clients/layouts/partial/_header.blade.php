<nav>
    <span class="menu__link">
        Меню
        <ul class="menu__ul">
            <li><a href="{{ route('main.page') }}">Главная</a></li>
            <li><a href="{{ route('sales.history') }}">История продаж</a></li>
            <li><a href="{{ route('comparison.index') }}">Сравнение</a></li>
            <li><a href="{{ route('charts.index') }}">Графики</a></li>
            <li><a href="{{ route('predictor.index') }}">Предсказатель</a></li>
        </ul>
    </span>
    <span class="menu__link user">
        Профиль
        <ul class="menu__ul">
            <li><a href="{{ route('main.page') }}">Настройки</a></li>
            <li><a href="{{ route('logout') }}">Выход</a></li>
        </ul>
    </span>
</nav>
