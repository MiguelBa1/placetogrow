import{d as l,s as c,r as m,x as _,k as d,y as f,o as t,h as a,z as v,b as h,t as i,A as g}from"./app-BIJ4Abzx.js";const V=l({__name:"TextInput",props:{modelValue:{required:!0},modelModifiers:{}},emits:["update:modelValue"],setup(u,{expose:e}){const r=c(u,"modelValue"),n=m(null);return _(()=>{var s,o;(s=n.value)!=null&&s.hasAttribute("autofocus")&&((o=n.value)==null||o.focus())}),e({focus:()=>{var s;return(s=n.value)==null?void 0:s.focus()}}),(s,o)=>d((t(),a("input",{class:"border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm","onUpdate:modelValue":o[0]||(o[0]=p=>r.value=p),ref_key:"input",ref:n},null,512)),[[f,r.value]])}}),b={class:"text-sm text-red-600"},M=l({__name:"InputError",props:{message:{}},setup(u){return(e,r)=>d((t(),a("div",null,[h("p",b,i(e.message),1)],512)),[[v,e.message]])}}),k=["for"],y={key:0},x={key:1},$=l({__name:"InputLabel",props:{forId:{},value:{}},setup(u){return(e,r)=>(t(),a("label",{for:e.forId,class:"block font-medium text-sm text-gray-700"},[e.value?(t(),a("span",y,i(e.value),1)):(t(),a("span",x,[g(e.$slots,"default")]))],8,k))}});export{$ as _,V as a,M as b};
