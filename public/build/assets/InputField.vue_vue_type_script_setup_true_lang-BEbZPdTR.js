import{d as b,j as t,o as a,h as o,t as l,i as d,b as f,K as g,n as y}from"./app-BCmDl1Zp.js";const h=["for"],C=["id","value","required","min","max"],v={key:1,class:"mt-1 text-sm text-red-600"},V="relative w-full bg-white border rounded-md shadow-sm px-3 py-2 text-left focus:outline-none focus:ring-1 sm:text-sm",k="border-red-500 focus:ring-red-500",w="border-gray-300 focus:ring-blue-500",B="disabled:bg-gray-200 disabled:cursor-not-allowed",N=b({__name:"InputField",props:{id:{},type:{},name:{},label:{},placeholder:{},className:{},modelValue:{},disabled:{type:Boolean},error:{},autoComplete:{},required:{type:Boolean},min:{},max:{}},emits:["update:modelValue"],setup(n,{emit:i}){const s=n,m=i,u=t(()=>`${V} ${s.error?k:w} ${B}`),p=t(()=>({id:s.id,name:s.name,type:s.type,placeholder:s.placeholder,disabled:s.disabled,autocomplete:s.autoComplete})),c=e=>{const r=e.target;m("update:modelValue",r.value)};return(e,r)=>(a(),o("div",{class:y(`w-full ${e.className??""}`)},[e.label?(a(),o("label",{key:0,for:e.id,class:"block text-sm font-medium text-gray-700 mb-1"},l(e.label),9,h)):d("",!0),f("input",g(p.value,{id:e.id,value:e.modelValue,onInput:c,class:u.value,required:e.required,min:e.min,max:e.max}),null,16,C),e.error?(a(),o("p",v,l(e.error),1)):d("",!0)],2))}});export{N as _};