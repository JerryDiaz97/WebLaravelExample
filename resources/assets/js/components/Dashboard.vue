<template>
<main class="main">
    <!-- Breadcrumb -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Escritorio</a></li>
    </ol>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                
            </div>
            <div class="car-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Ingresos</h4>
                            </div>
                            <div class="card-content">
                                <div class="ct-chart">
                                    <canvas id="entries">                                                
                                    </canvas>
                                </div>
                            </div>
                            <div class="card-footer">
                                <p>Compras de los últimos meses.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-chart">
                            <div class="card-header">
                                <h4>Ventas</h4>
                            </div>
                            <div class="card-content">
                                <div class="ct-chart">
                                    <canvas id="sales">                                                
                                    </canvas>
                                </div>
                            </div>
                            <div class="card-footer">
                                <p>Ventas de los últimos meses.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

</template>
<script>
    export default {
        data(){
            return {
                varEntry : null,
                charEntry : null,
                entries : [],
                varTotalEntry : [],
                varMonthEntry : [],
                varSale : null,
                charSale : null,
                sales : [],
                varTotalSale : [],
                varMonthSale : [],

            }

        },
        mounted(){
            this.getEntries();
            this.getSales();

        },
        methods : {
            getEntries(){
                let me=this;
                var url= '/dashboard';
                axios.get(url).then(function (response) {
                    var answer = response.data;
                    me.entries = answer.entries;
                    //cargamos los datos del chart
                    me.loadEntries();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            getSales(){
                let me=this;
                var url= '/dashboard';
                axios.get(url).then(function (response) {
                    var answer= response.data;
                    me.sales = answer.sales;
                    //cargamos los datos del chart
                    me.loadSales();
                })
                .catch(function (error) {
                    console.log(error);
                });
            },
            loadEntries(){
                let me=this;
                me.entries.map(function(x){
                    me.varMonthEntry.push(x.mes);
                    me.varTotalEntry.push(x.total);
                });
                me.varEntry=document.getElementById('entries').getContext('2d');

                me.charEntry = new Chart(me.varEntry, {
                    type: 'bar',
                    data: {
                        labels: me.varMonthEntry,
                        datasets: [{
                            label: 'Ingresos',
                            data: me.varTotalEntry,
                            backgroundColor: 'rgba(255, 99, 132, 0.2)',
                            borderColor: 'rgba(255, 99, 132, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            },
            loadSales(){
                let me=this;
                me.sales.map(function(x){
                    me.varMonthSale.push(x.mes);
                    me.varTotalSale.push(x.total);
                });
                me.varSale=document.getElementById('sales').getContext('2d');

                me.charSale = new Chart(me.varSale, {
                    type: 'bar',
                    data: {
                        labels: me.varMonthSale,
                        datasets: [{
                            label: 'Ventas',
                            data: me.varTotalSale,
                            backgroundColor: 'rgba(54, 162, 235, 0.2)',
                            borderColor: 'rgba(54, 162, 235, 0.2)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            }
        }
    }
</script>


