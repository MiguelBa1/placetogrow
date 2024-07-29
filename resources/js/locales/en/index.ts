import { enCommon } from './common';
import enAuth from './auth';
import enProfile from './profile';
import enRolePermission from './rolePermissions'
import enMicrosites from './microsites';
import enPayment from './payments';
import enUsers from './users';
import enHome from './home';
import enInvoices from './invoices';
import enLayouts from './layouts';
import enComponents from './components';

export default {
    common: enCommon,
    auth: enAuth,
    profile: enProfile,
    rolePermissions: enRolePermission,
    microsites: enMicrosites,
    payments: enPayment,
    users: enUsers,
    home: enHome,
    invoices: enInvoices,
    layouts: enLayouts,
    components: enComponents,
};
