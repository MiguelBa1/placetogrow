import{C as r,p as n,a as i,b as p,e as d,c as m,L as c,P as f,f as u}from"./index-B4YHFS-G.js";import{d as _,u as y,o as b,c as g,e as C}from"./app-BCmDl1Zp.js";import{d as h}from"./dayjs.min-Cm2y4O4P.js";const A=_({__name:"PaymentsOverTimeChart",props:{data:{}},setup(t){r.register(n,i,p,d,m,c,f);const{t:s}=y(),e=t,o={labels:[...new Set(e.data.map(a=>h(a.day).format("MMM DD")))],datasets:[{label:"COP",borderColor:"#42A5F5",data:e.data.filter(a=>a.currency==="COP").map(a=>parseFloat(a.total_amount)),fill:!1},{label:"USD",borderColor:"#66BB6A",data:e.data.filter(a=>a.currency==="USD").map(a=>parseFloat(a.total_amount)),fill:!1}]},l={responsive:!0,maintainAspectRatio:!1,plugins:{legend:{position:"top"},title:{display:!0,text:s("dashboard.index.payments_over_time"),font:{family:"Poppins",size:18}}},scales:{x:{title:{display:!1}},y:{title:{display:!1},beginAtZero:!0}}};return(a,P)=>(b(),g(C(u),{data:o,options:l,height:"400"}))}});export{A as _};