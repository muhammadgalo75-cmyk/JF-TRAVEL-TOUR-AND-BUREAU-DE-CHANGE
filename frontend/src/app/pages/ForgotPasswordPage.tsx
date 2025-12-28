import { useState } from 'react';
import { Mail, Plane, ArrowLeft, CheckCircle } from 'lucide-react';
import { Button } from '../components/ui/button';
import { Card } from '../components/ui/card';
import { Input } from '../components/ui/input';
import { Label } from '../components/ui/label';
import { toast } from 'sonner';
import { auth } from '../../config/firebase';
import { sendPasswordResetEmail } from 'firebase/auth';

interface ForgotPasswordPageProps {
  onNavigate: (page: string) => void;
}

export function ForgotPasswordPage({ onNavigate }: ForgotPasswordPageProps) {
  const [email, setEmail] = useState('');
  const [isLoading, setIsLoading] = useState(false);
  const [isSubmitted, setIsSubmitted] = useState(false);

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    
    if (!email) {
      toast.error('Please enter your email address');
      return;
    }

    try {
      setIsLoading(true);
      await sendPasswordResetEmail(auth, email);
      setIsSubmitted(true);
      toast.success('Password reset email sent! Check your inbox.');
    } catch (error: any) {
      if (error.code === 'auth/user-not-found') {
        toast.error('No account found with this email address');
      } else if (error.code === 'auth/invalid-email') {
        toast.error('Invalid email address');
      } else {
        toast.error(error.message || 'Failed to send reset email');
      }
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-blue-600 via-blue-700 to-cyan-600 flex items-center justify-center p-4">
      <div className="w-full max-w-md">
        {/* Logo */}
        <div className="text-center mb-8">
          <div className="inline-flex items-center gap-2 mb-4">
            <div className="w-12 h-12 bg-white rounded-lg flex items-center justify-center">
              <Plane className="w-7 h-7 text-blue-600" />
            </div>
            <div className="text-left">
              <h1 className="text-2xl font-bold text-white">JF Travels</h1>
              <p className="text-sm text-blue-100">Bureau de Change</p>
            </div>
          </div>
        </div>

        {/* Card */}
        <Card className="p-8">
          {!isSubmitted ? (
            <>
              <div className="text-center mb-6">
                <h2 className="text-2xl font-bold mb-2">Reset Password</h2>
                <p className="text-gray-600">Enter your email address and we'll send you a link to reset your password</p>
              </div>

              <form onSubmit={handleSubmit} className="space-y-4">
                <div>
                  <Label htmlFor="email" className="flex items-center gap-2">
                    <Mail className="w-4 h-4" />
                    Email Address
                  </Label>
                  <Input
                    id="email"
                    type="email"
                    placeholder="john@example.com"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    className="mt-2"
                    required
                  />
                </div>

                <Button
                  type="submit"
                  className="w-full bg-blue-600 hover:bg-blue-700 h-11"
                  disabled={isLoading}
                >
                  {isLoading ? 'Sending...' : 'Send Reset Link'}
                </Button>
              </form>

              <div className="mt-6">
                <button
                  onClick={() => onNavigate('login')}
                  className="w-full flex items-center justify-center gap-2 text-gray-600 hover:text-gray-900 transition-colors"
                >
                  <ArrowLeft className="w-4 h-4" />
                  Back to Login
                </button>
              </div>
            </>
          ) : (
            <>
              <div className="text-center">
                <div className="flex justify-center mb-4">
                  <CheckCircle className="w-16 h-16 text-green-500" />
                </div>
                <h2 className="text-2xl font-bold mb-2">Check Your Email</h2>
                <p className="text-gray-600 mb-6">
                  We've sent a password reset link to <span className="font-semibold text-gray-900">{email}</span>
                </p>
                <p className="text-sm text-gray-500 mb-6">
                  Click the link in the email to reset your password. If you don't see the email, check your spam folder.
                </p>

                <div className="space-y-3">
                  <Button
                    onClick={() => onNavigate('login')}
                    className="w-full bg-blue-600 hover:bg-blue-700 h-11"
                  >
                    Back to Login
                  </Button>
                  <Button
                    onClick={() => {
                      setEmail('');
                      setIsSubmitted(false);
                    }}
                    variant="outline"
                    className="w-full h-11"
                  >
                    Try Another Email
                  </Button>
                </div>
              </div>
            </>
          )}
        </Card>

        <p className="text-center text-sm text-blue-100 mt-6">
          Need help? Contact our support team
        </p>
      </div>
    </div>
  );
}
