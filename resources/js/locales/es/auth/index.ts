import { esConfirmPassword } from './ConfirmPassword';
import { esForgotPassword } from './ForgotPassword';
import { esLogin } from './Login';
import { esRegister } from './Register';
import { esResetPassword } from './ResetPassword';
import { esVerifyEmail } from './VerifyEmail';

export default {
    confirmPassword: esConfirmPassword,
    forgotPassword: esForgotPassword,
    login: esLogin,
    register: esRegister,
    resetPassword: esResetPassword,
    verifyEmail: esVerifyEmail,
};
