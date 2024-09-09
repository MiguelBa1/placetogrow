import{d as f,j as d,o as r,h as s,t as n,i,b as u,D as g,n as h}from"./app-BIJ4Abzx.js";const v=["for"],w=["id","value","required"],C={key:1,class:"mt-1 text-sm text-red-600"},y="relative w-full bg-white border rounded-md shadow-sm px-3 py-2 text-left focus:outline-none focus:ring-1 sm:text-sm",x="border-red-500 focus:ring-red-500",V="border-gray-300 focus:ring-blue-500",B="disabled:bg-gray-200 disabled:cursor-not-allowed",k=f({__name:"TextareaField",props:{id:{},name:{},label:{},placeholder:{},className:{},modelValue:{},disabled:{type:Boolean},error:{},autoComplete:{},required:{type:Boolean},rows:{}},emits:["update:modelValue"],setup(o,{emit:l}){const a=o,c=l,m=d(()=>`${y} ${a.error?x:V} ${B}`),p=d(()=>({id:a.id,name:a.name,placeholder:a.placeholder,disabled:a.disabled,autocomplete:a.autoComplete,rows:a.rows||3})),b=e=>{const t=e.target;c("update:modelValue",t.value)};return(e,t)=>(r(),s("div",{class:h(`w-full ${e.className??""}`)},[e.label?(r(),s("label",{key:0,for:e.id,class:"block text-sm font-medium text-gray-700 mb-1"},n(e.label),9,v)):i("",!0),u("textarea",g(p.value,{id:e.id,value:e.modelValue,onInput:b,class:m.value,required:e.required}),null,16,w),e.error?(r(),s("p",C,n(e.error),1)):i("",!0)],2))}});function q(o,l){return r(),s("svg",{xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 16 16",fill:"currentColor","aria-hidden":"true","data-slot":"icon"},[u("path",{"fill-rule":"evenodd",d:"M8 1a7 7 0 1 0 0 14A7 7 0 0 0 8 1ZM4.5 3.757a5.5 5.5 0 1 0 6.857-.114l-.65.65a.707.707 0 0 0-.207.5c0 .39-.317.707-.707.707H8.427a.496.496 0 0 0-.413.771l.25.376a.481.481 0 0 0 .616.163.962.962 0 0 1 1.11.18l.573.573a1 1 0 0 1 .242 1.023l-1.012 3.035a1 1 0 0 1-1.191.654l-.345-.086a1 1 0 0 1-.757-.97v-.305a1 1 0 0 0-.293-.707L6.1 9.1a.849.849 0 0 1 0-1.2c.22-.22.22-.58 0-.8l-.721-.721A3 3 0 0 1 4.5 4.257v-.5Z","clip-rule":"evenodd"})])}export{k as _,q as r};
