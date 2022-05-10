using System;
using System.Data;
using System.Configuration;
using System.Collections;
using System.Web;
using System.Web.Security;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Web.UI.WebControls.WebParts;
using System.Web.UI.HtmlControls;

public partial class MasterCommulative : System.Web.UI.MasterPage
{
    commonclass clscon = new commonclass();
    protected void Page_Load(object sender, EventArgs e)
    {
        if (Page.IsPostBack == false)
        {
            try
            {



                lblcity.Text = clscon.Return_string("select concat(firstname,' ',lastname) as stuname from tbstuadmission where studentid=" + Convert.ToInt32(Session["stuid"]) + "");
                batch.Text = clscon.Return_string("select a.batchname from batchmaster a,studentdetail b where a.batchid=b.batchid and b.studentid=" + Convert.ToInt32(Session["stuid"]) + "");
                //uid = 14110007;
                int domainid = clscon.Return_Int("select a.domainid from tbtcdomaincourse a,tbcoursemaster b,tbstuadmission c where c.studentid=" + Convert.ToInt32(Session["stuid"]) + " and c.courserep=b.courserep and b.courseid=a.courseid");
                try
                {
                    clscreatestudentmenu clsmenu = new clscreatestudentmenu();
                    Session["dataonlinelink"] = clsmenu.PutOnlineLinkVisible(domainid, Convert.ToInt32(Session["stuid"]));

                }
                catch { }
                lblidnew.Text = Session["stuid"].ToString();
                if (Convert.ToInt32(Session["stuid"]) == 0)
                {
                    Response.Redirect("index.php?action=logout");
                }
            }
            catch (Exception ex)
            {
                Response.Write(ex.Message);
            }

        }
    }
    protected void LinkButton1_Click(object sender, EventArgs e)
    {
        if (Session["studentid"] != null)
        {
            if (Convert.ToString(Session["stutype"]) == "G")
            {
                Response.Redirect("index.php?action=welcomeStudent");
            }
            else if (Convert.ToString(Session["stutype"]) == "R")
            {
                Response.Redirect("index.php?action=welcomeStudentb");
            }
            else
            {
                Response.Redirect("index.php?action=welcomeStudent");
            }
        }
    }
    protected void lnklogout_Click(object sender, EventArgs e)
    {
        Session.RemoveAll();
        Session.Abandon();
        Response.Redirect("index.php?action=logout");
    }
}
