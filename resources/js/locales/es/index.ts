import { esCommon } from './common';
import esAuth from './auth';
import esProfile from './profile';
import esRolePermission from './rolePermissions'
import esMicrosites from './microsites';
import esPayment from './payments';
import esUsers from './users';
import esHome from './home';
import esLayouts from './layouts';
import esComponents from './components';

export default {
    common: esCommon,
    auth: esAuth,
    profile: esProfile,
    rolePermissions: esRolePermission,
    microsites: esMicrosites,
    payments: esPayment,
    users: esUsers,
    home: esHome,
    layouts: esLayouts,
    components: esComponents,
};
