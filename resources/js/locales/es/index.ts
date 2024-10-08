import { esCommon } from './common';
import esAuth from './auth';
import esCustomerInvoices from './customerInvoices';
import esCustomerSubscriptions from './customerSubscriptions';
import esProfile from './profile';
import esRolePermission from './rolePermissions'
import esMicrosites from './microsites';
import esPayment from './payments';
import esUsers from './users';
import esHome from './home';
import esInvoices from './invoices';
import esSubscriptions from './subscriptions';
import esTransactions from './transactions';
import esLayouts from './layouts';
import esComponents from './components';

export default {
    common: esCommon,
    auth: esAuth,
    customerInvoices: esCustomerInvoices,
    customerSubscriptions: esCustomerSubscriptions,
    profile: esProfile,
    rolePermissions: esRolePermission,
    microsites: esMicrosites,
    payments: esPayment,
    users: esUsers,
    home: esHome,
    invoices: esInvoices,
    subscriptions: esSubscriptions,
    transactions: esTransactions,
    layouts: esLayouts,
    components: esComponents,
};
