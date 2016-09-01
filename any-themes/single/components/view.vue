<style></style>
<template>
	我是内页
</template>
<script>
export default {
	data(){
		return {
			code :'d1'
		}
	},
	created(){

		this.$http.get(this.$parent.webService + '/account/test').then(function(response){
			console.log(response)
		})
	},
	ready(){
		console.log(this)
	}
}
</script>