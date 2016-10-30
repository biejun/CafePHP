<style>
</style>
<template>
<div>
	<a v-link="{path:'/'}">首页</a>
	<a v-link="{path:'/view'}">内页</a>
     <router-view></router-view>
</div>
</template>

<script>
export default {
    data() {
        return {
            webService : 'http://localhost:8888'
        }
    }
}
</script>