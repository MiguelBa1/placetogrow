import{d as c,o as a,c as l,w as m,b as s,t as p,e as u,l as d}from"./app-BCmDl1Zp.js";const h={class:"flex justify-center items-center"},g=["src","alt"],f={class:"text-xl text-center"},y=c({__name:"MicrositeCard",props:{microsite:{}},setup(o){const{microsite:e}=o,i=t=>{const r=t.target;r.src="/images/placeholder.png"},n=()=>e.type==="basic"?route("basic-payments.show",{microsite:e.slug}):e.type==="invoice"?route("invoice-payments.show",{microsite:e.slug}):e.type==="subscription"?route("subscription-payments.show",{microsite:e.slug}):(console.error("Unknown microsite type:",e.type),"#");return(t,r)=>(a(),l(u(d),{href:n(),class:"grid gap-2 p-4 max-w-sm rounded overflow-hidden border bg-white shadow-sm hover:text-blue-500 cursor-pointer"},{default:m(()=>[s("div",h,[s("img",{class:"h-24 w-24",src:t.microsite.logo,alt:t.microsite.name,onError:i,loading:"lazy"},null,40,g)]),s("div",f,p(t.microsite.name),1)]),_:1},8,["href"]))}});export{y as _};
