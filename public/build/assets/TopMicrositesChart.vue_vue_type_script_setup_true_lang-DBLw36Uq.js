import{C as i,p as c,a as p,b as l,B as d,c as m,L as u,d as _}from"./index-B4YHFS-G.js";import{d as g,u as f,o as h,c as B,e as x}from"./app-BCmDl1Zp.js";const A=g({__name:"TopMicrositesChart",props:{data:{}},setup(s){i.register(c,p,l,d,m,u);const{t:e}=f(),t=s,n=Math.max(...t.data.map(a=>a.transaction_count)),o={labels:t.data.map(a=>a.microsite_name),datasets:[{label:"Transactions",backgroundColor:["#42A5F5","#66BB6A","#FFCE56","#FF6384","#36A2EB"],data:t.data.map(a=>a.transaction_count)}]},r={responsive:!0,maintainAspectRatio:!1,plugins:{legend:{display:!1},title:{display:!0,text:e("dashboard.index.top_microsites"),font:{family:"Poppins",size:16}}},scales:{y:{beginAtZero:!0,suggestedMax:n+2}}};return(a,C)=>(h(),B(x(_),{data:o,options:r,height:"300"}))}});export{A as _};
