import { enCommon } from './common';
import enAuth from './auth';
import enProfile from './profile';
import enRolePermission from './rolePermissions'
import enMicrosites from './microsites';
import esPayment from './payments';
import enHome from './home';
import enLayouts from './layouts';
import enComponents from './components';

export default {
    common: enCommon,
    auth: enAuth,
    profile: enProfile,
    rolePermissions: enRolePermission,
    microsites: enMicrosites,
    payments: esPayment,
    home: enHome,
    layouts: enLayouts,
    components: enComponents,
};
