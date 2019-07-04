<!-- Main Content -->
    @extends('main')
    @section('content')

    <template v-if = "menu == 0">   
    <!--Reference to the vue component-->
        <h1>Contenido del Menú 0</h1>
    </template>

    <template v-if = "menu == 1">   
        <category></category>
    </template>

    <template v-if = "menu == 2">   
        <product></product>
    </template>

    <template v-if = "menu == 3">   
        <h1>Contenido del Menú 3</h1>
    </template>

    <template v-if = "menu == 4">   
        <provider></provider>
    </template>

    <template v-if = "menu == 5">   
        <h1>Contenido del Menú 5</h1>
    </template>

    <template v-if = "menu == 6">   
        <client></client>
    </template>

    <template v-if = "menu == 7">   
        <h1>Contenido del Menú 7</h1>
    </template>

    <template v-if = "menu == 8">   
        <h1>Contenido del Menú 8</h1>
    </template>

    <template v-if = "menu == 9">   
        <h1>Contenido del Menú 9</h1>
    </template>

    <template v-if = "menu == 10">   
        <h1>Contenido del Menú 10</h1>
    </template>

    <template v-if = "menu == 11">   
        <h1>Contenido del Menú 11</h1>
    </template>

    <template v-if = "menu == 12">   
        <h1>Contenido del Menú 12</h1>
    </template>
    
    @endsection
<!-- /End of the main content -->