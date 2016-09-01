<style>
.logo{
    padding-top: 50px;
    text-align:center;
}
.logo img{
    width:160px;
}
.logo h1 {
    font-family: 'Dosis', 'Source Sans Pro', 'Helvetica Neue', Arial, sans-serif;
    font-weight: 300;
    font-size: 3em;
    color:#000;
}
.logo p {
    color:#999;
}
.content{
    margin:auto;
    width:600px;
}
.content h3{
    font-size: 1rem;
    font-weight:300;
    color:#666;
}
.content input{
    display: block;
    padding:10px;
    width:100%;
    outline: 0
}
.button{
    display:block;
    margin:20px auto;
    outline: 0;
    width:60px;
    height:60px;
    font-size: 1.2rem;
    background-color:#2F9E75;
    color:#fff;
    border-radius: 50%;
    border:none;
    cursor: pointer;
}
pre{
    font-size: 12px;
    color:#41B883
}
.demo{
    padding:10px;
}
</style>

<template>
<div>
    {{title}}

    <input type="text" v-model="title"/>
</div>
</template>

<script>
export default {
    data() {
        return {
            title:"222"
        }
    },
    ready() {
        //console.log(this)
    }
}
</script>