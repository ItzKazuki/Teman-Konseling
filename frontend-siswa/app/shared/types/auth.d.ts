interface Login {
  token: string;
  type: string;
  user: User;
}

interface CheckOtp {
  resetToken: string;
}

interface LoginRequest {
  email: string;
  password: string;
  remember_me: boolean
}

interface ForgotPasswordRequest {
  email: string;
}

interface CheckOtpRequest {
  email: string;
  otp: string;
}

interface ResetPasswordRequest {
  reset_token: string;
  email: string;
  password: string;
  password_confirmation: string;
}
