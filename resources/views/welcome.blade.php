<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Language Change</title>
</head>

<body>
    <select onchange="location.href='{{ url('lang') }}/' + this.value" class="form-select form-select-sm"
        aria-label="{{ __('menu.change_language') }}">
        <option value="en" {{ app()->getLocale() == 'en' ? 'selected' : '' }}>English</option>
        <option value="ne" {{ app()->getLocale() == 'ne' ? 'selected' : '' }}>नेपाली</option>
        <option value="mai" {{ app()->getLocale() == 'mai' ? 'selected' : '' }}>मैथili</option>
    </select>


    
    {{ __('menu.home') }}
    {{ __('menu.gallery') }}
    {{ __('menu.events') }}
</body>

</html>