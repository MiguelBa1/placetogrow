import{d as n,u as c,m,o as d,h as p,b as a,t as h,e,a as t,w as _,g as f,O as u}from"./app-Bpvmybdt.js";import{_ as x}from"./InputField.vue_vue_type_script_setup_true_lang-CfQ2MUfz.js";import"./ApplicationLogo-B_getK0f.js";import{_ as v}from"./Button.vue_vue_type_script_setup_true_lang-3Poo1hdF.js";import"./PrimaryButton-DxnYWVKS.js";import{r as y}from"./MagnifyingGlassIcon-CwszkKX1.js";const g={class:"flex items-center"},w={class:"font-semibold text-xl text-gray-800"},V={class:"flex items-center gap-2"},$=n({__name:"HomeHeader",setup(b){const{t:o}=c(),s=m(""),l=()=>{s.value&&u.visit(route("home",{search:s.value}),{only:["microsites"]})};return(B,r)=>(d(),p("form",{class:"flex gap-4",onSubmit:f(l,["prevent"])},[a("div",g,[a("h2",w,h(e(o)("home.index.header")),1)]),a("div",V,[t(e(x),{id:"search",type:"text",class:"w-64",placeholder:e(o)("home.index.searchPlaceholder"),modelValue:s.value,"onUpdate:modelValue":r[0]||(r[0]=i=>s.value=i)},null,8,["placeholder","modelValue"]),t(e(v),{type:"submit",variant:"secondary"},{default:_(()=>[t(e(y),{class:"h-5 w-5"})]),_:1})])],32))}});export{$ as _};