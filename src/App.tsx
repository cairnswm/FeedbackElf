import React from "react";
import { BrowserRouter as Router, Routes, Route } from "react-router-dom";
import LandingPage from "./pages/landing/LandingPage";
import LoginPage from "./auth/LoginPage";
import RegisterPage from "./auth/RegisterPage";
import ForgotPage from "./auth/ForgotPage";
import HomePage from "./pages/home/home";
import ProfilePage from "./pages/profile/profile";
import SystemPage from "./pages/system/system";
import SubscriptionsPage from "./pages/subscriptions/SubscriptionsPage";
import BuySubscriptionPage from "./pages/subscriptions/BuySubscriptionPage";
import PaidSubscriptionsPage from "./pages/subscriptions/PaidSubscriptionsPage";
import { useAuth } from "./auth/hooks/useAuth";

function App() {
  const { user } = useAuth();
  console.log("========= USER ========", user);
  return (
    <Router>
      <div className="min-h-screen h-full flex flex-col bg-gray-100">
        <Routes>
          <Route path="/" element={<LandingPage />} />
          <Route path="/login" element={<LoginPage />} />
          <Route path="/register" element={<RegisterPage />} />
          <Route path="/forgot" element={<ForgotPage />} />
          <Route path="/home" element={user ? <HomePage /> : <LoginPage />} />
          <Route path="/profile" element={user ? <ProfilePage /> : <LoginPage />} />
          <Route path="/system" element={<SystemPage />} />
          <Route path="/subscriptions" element={user ? <SubscriptionsPage /> : <LoginPage />} />
          <Route path="/subscriptions/buy" element={user ? <BuySubscriptionPage /> : <LoginPage />} />
          <Route path="/subscriptions/paid" element={user ? <PaidSubscriptionsPage /> : <LoginPage />} />
          <Route path="*" element={<HomePage />} />
        </Routes>
      </div>
    </Router>
  );
}

export default App;
