import { esConfirmPassword } from './ConfirmPassword';
import { esForgotPassword } from './ForgotPassword';
import { esLogin } from './Login';
import { esResetPassword } from './ResetPassword';
import { esVerifyEmail } from './VerifyEmail';

export default {
    confirmPassword: esConfirmPassword,
    forgotPassword: esForgotPassword,
    login: esLogin,
    resetPassword: esResetPassword,
    verifyEmail: esVerifyEmail,
};
