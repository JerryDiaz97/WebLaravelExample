<!-- Main Content -->
    @extends('main')
    @section('content')

    @if(Auth::check())
            @if(Auth::user()->id_role == 1)
                <template v-if = "menu == 0">   
                <!--Reference to the vue component-->
                    <dashboard></dashboard>
                </template>

                <template v-if = "menu == 1">   
                    <category></category>
                </template>

                <template v-if = "menu == 2">   
                    <product></product>
                </template>

                <template v-if = "menu == 3">   
                    <entry></entry>
                </template>

                <template v-if = "menu == 4">   
                    <provider></provider>
                </template>

                <template v-if = "menu == 5">   
                    <sale></sale>
                </template>

                <template v-if = "menu == 6">   
                    <client></client>
                </template>

                <template v-if = "menu == 7">   
                    <user></user>
                </template>

                <template v-if = "menu == 8">   
                    <role></role>
                </template>

                <template v-if = "menu == 9">   
                    <queryincome></queryincome>
                </template>

                <template v-if = "menu == 10">   
                    <querysale></querysale>
                </template>

                <template v-if = "menu == 11">   
                    <h1>Ayuda</h1>
                </template>

                <template v-if = "menu == 12">   
                    <h1>Acerca de</h1>
                </template>

            @elseif(Auth::user()->id_role ==2)
                <template v-if = "menu == 0">   
                <!--Reference to the vue component-->
                    <dashboard></dashboard>
                </template>
                <template v-if = "menu == 5">   
                    <sale></sale>
                </template>

                <template v-if = "menu == 6">   
                    <client></client>
                </template>
                
                <template v-if = "menu == 10">   
                    <querysale></querysale>
                </template>

                <template v-if = "menu == 11">   
                    <h1>Ayuda</h1>
                </template>

                <template v-if = "menu == 12">   
                    <h1>Acerca de</h1>
                </template>

            @elseif(Auth::user()->id_role ==3)
                <template v-if = "menu == 0">   
                <!--Reference to the vue component-->
                    <dashboard></dashboard>
                </template>
                <template v-if = "menu == 1">   
                    <category></category>
                </template>

                <template v-if = "menu == 2">   
                    <product></product>
                </template>

                <template v-if = "menu == 3">   
                    <entry></entry>
                </template>

                <template v-if = "menu == 4">   
                    <provider></provider>
                </template>

                <template v-if = "menu == 9">   
                    <queryincome></queryincome>
                </template>

                <template v-if = "menu == 11">   
                    <h1>Ayuda</h1>
                </template>

                <template v-if = "menu == 12">   
                    <h1>Acerca de</h1>
                </template>

            @else
            
            @endif
    @endif
        
    @endsection
<!-- /End of the main content -->