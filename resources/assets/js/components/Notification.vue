<template>
    <li class="nav-item d-md-down-none">
        <a class="nav-link" href="#" data-toggle="dropdown">
            <i class="icon-bell"></i>
            <span class="badge badge-pill badge-danger">{{notifications.length}}</span>
        </a>
        <div class="dropdown-menu dropdown-menu-right">
            <div class="dropdown-header text-center">
                <strong>Notificaciones</strong>
            </div>
            <div v-if="notifications.length">
            <li v-for="item in listing" :key="item.id">
            <a class="dropdown-item" href="#">
                <i class="fa fa-envelope-o"></i> {{item.entries.msj}}
                <span class="badge badge-success">{{item.entries.num}}</span>
            </a>
            <a class="dropdown-item" href="#"><!--{{item.data.datos.sales.msj}}-->
                <i class="fa fa-tasks"></i> {{item.sales.msj}}
                <span class="badge badge-danger">{{item.sales.num}}</span>
            </a>
            </li>
            </div>
            <div v-else>
                <a><span>No tiene Notificaciones</span></a>
            </div>
        </div>
    </li>    
</template>
<script>
export default {
    props : ['notifications'],
    data () {
        return {
            arrayNotifications : []

        }
    },
    computed : {
        listing: function(){
            //return this.notifications[0];
            this.arrayNotifications = Object.values(this.notifications[0]);
            //return Object.values(this.arrayNotifications[0]);
            if(this.notifications == '') {
                return this.arrayNotifications = [];
            } 
            else{
                //save the last notification added
                this.arrayNotifications = Object.values(this.notifications[0]);
                //Validation for out range index
                if(this.arrayNotifications.length > 3) {
                    //If length>3 then the notifications are obtained from the 
                    return Object.values(this.arrayNotifications[4]);
                }
                else {
                    // if length < 3 then the notification are obtained from ajax
                    return Object.values(this.arrayNotifications[0]);
                }
            }
        }
    }
}
</script>