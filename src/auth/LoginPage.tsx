import { accessElf } from "./utils/accessElf"
import { useState } from "react"
import { useAuth } from "./hooks/useAuth"
import { useNavigate } from "react-router-dom"
import Button from "../components/Button"

const LoginPage = () => {
  accessElf.track("LoginPage")
  
  const auth = useAuth();
  const navigate = useNavigate();

  if (!auth) throw new Error("Auth context is null");
  const { login } = auth;

  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");

  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const res = await login(email, password);
      console.log("Login Result", res)
      navigate("/home")
    } catch (error) {
      console.error("Login failed:", error);
    }
  };

  return (
  <div className="min-h-screen bg-gray-100 flex items-center justify-center p-4">
  <div className="max-w-md w-full bg-white rounded-xl shadow-lg p-4 relative">
    <button
      className="absolute top-4 right-4 text-gray-500 hover:text-gray-700"
      onClick={() => navigate("/")}
    >
      X
    </button>
    <h2 className="text-2xl font-bold text-gray-900 mb-3 text-center">Sign In</h2>
    
    <form className="space-y-4">
      <div>
        <label className="block text-sm font-medium text-gray-700 mb-1">Email</label>
        <input 
          type="email" 
          className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
          placeholder="your@email.com"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
        />
      </div>

      <div>
        <label className="block text-sm font-medium text-gray-700 mb-1">Password</label>
        <input 
          type="password" 
          className="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none transition-all"
          placeholder="••••••••"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
        />
      </div>

      <div className="flex items-center justify-between">
        <label className="flex items-center">
          <input type="checkbox" className="rounded border-gray-300 text-primary focus:ring-indigo-500"/>
          <span className="ml-2 text-sm text-gray-600">Remember me</span>
        </label>
      </div>
      <div>
        
      <a href="#" className="text-sm text-primary hover:text-indigo-500">Forgot password?</a>
      </div>

      <Button className="w-full bg-primary hover:bg-indigo-700 text-white font-medium py-2.5 rounded-lg transition-colors"
        onClick={handleLogin}>
        Sign In
      </Button>
    </form>

    <div className="mt-3 text-center text-sm text-gray-600">
      Don't have an account? 
      <Button variant="light" href="/register" className="text-primary hover:text-indigo-500 font-medium ms-2">Sign up</Button>
    </div>
  </div>
</div>)}

export default LoginPage;