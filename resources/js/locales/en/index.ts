import { enCommon } from './common';
import enAuth from './auth';
import enCustomerInvoices from './customerInvoices';
import esSubscriptions from './subscriptions';
import enProfile from './profile';
import enRolePermission from './rolePermissions'
import enMicrosites from './microsites';
import enPayment from './payments';
import enUsers from './users';
import enHome from './home';
import enInvoices from './invoices';
import enPlans from './plans';
import enTransactions from "./transactions";
import enLayouts from './layouts';
import enComponents from './components';

export default {
    common: enCommon,
    auth: enAuth,
    customerInvoices: enCustomerInvoices,
    subscriptions: esSubscriptions,
    profile: enProfile,
    rolePermissions: enRolePermission,
    microsites: enMicrosites,
    payments: enPayment,
    users: enUsers,
    home: enHome,
    invoices: enInvoices,
    plans: enPlans,
    transactions: enTransactions,
    layouts: enLayouts,
    components: enComponents,
};
