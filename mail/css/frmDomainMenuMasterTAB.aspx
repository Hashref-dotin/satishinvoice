<%@ Page Language="C#" MasterPageFile="~/MasterPageEnquiry.master" Theme="skinFile" AutoEventWireup="true" CodeFile="frmDomainMenuMasterTAB.aspx.cs" Inherits="frmDomainMenuMaster" Title="Domain Menu Master" %>
<%@ Register Assembly="AjaxControlToolkit" Namespace="AjaxControlToolkit" TagPrefix="cc1" %>

<asp:Content ID="Content1" ContentPlaceHolderID="ContentPlaceHolder1" Runat="Server">
    <table id="table1" align="center" border="0" cellpadding="0" cellspacing="0" width="785">
        <tr>
            <td style="text-align: center">
                <asp:Label ID="lbl_header" runat="Server" Font-Bold="True" Width="785px" CssClass="lbold">Test Menu Master</asp:Label></td>
        </tr>
        <tr>
            <td style="text-align: right">
                <asp:ScriptManager id="ScriptManager1" runat="server">
                </asp:ScriptManager>
                <asp:LinkButton ID="lnktab" runat="server" ForeColor="#006600" OnClick="lnktab_Click" Font-Bold="True" Font-Names="Rod" Font-Size="11pt">Add Tab</asp:LinkButton>
                <asp:LinkButton ID="lnkaddmenu" runat="server" Font-Bold="True" Font-Names="Rod"
                    Font-Size="11pt" ForeColor="#006600" OnClick="lnkaddmenu_Click">Add Menu</asp:LinkButton>
                ||
                <asp:LinkButton ID="lnkaddsubmenu" runat="server" ForeColor="#006600" OnClick="lnkaddsubmenu_Click" Font-Bold="True" Font-Names="Rod" Font-Size="11pt">Add Sub Menu</asp:LinkButton>&nbsp;</td>
        </tr>
        <tr>
            <td>
                <asp:Panel ID="pnlmain" runat="server" CssClass="modalPopupVip" Visible="False">
                <table id="table2" align="center" border="0" cellpadding="0" cellspacing="0" width="500">
                    <tr>
                        <td style="height: 22px">
                            <asp:Label ID="Label6" runat="server" CssClass="boldtext">Domain</asp:Label>
                        </td>
                        <td style="height: 22px">
                            <asp:DropDownList ID="ddldomain" runat="server" Width="238px" AutoPostBack="True" CssClass="text" OnSelectedIndexChanged="ddldomain_SelectedIndexChanged">
                                <asp:ListItem>--Select--</asp:ListItem>
                                <asp:ListItem Value="1">MBA</asp:ListItem>
                            </asp:DropDownList></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="height: 22px">
                            <table>
                                <tr>
                                    <td colspan="2">
                                        <asp:Panel ID="pnlmenu"  runat="server" Visible="False">
                                            &nbsp;<table>
                                                <tr>
                                                    <td>
                                    <fieldset style="valign: top" ><legend class="boldtext" >Add Menu</legend>
                                        <asp:RequiredFieldValidator ID="RequiredFieldValidator3" runat="server" ControlToValidate="txtmenuitem"
                                            CssClass="text" ErrorMessage="*" ValidationGroup="a"></asp:RequiredFieldValidator><br />
                                        <table style="color: #000000">
                                            <tr>
                                                <td style="width: 100px">
                                                    <asp:Label ID="Label9" runat="server" CssClass="boldtext" Text="Tab" Width="87px"></asp:Label></td>
                                                <td style="width: 100px">
                                                    <asp:DropDownList ID="ddltabmenu" runat="server" CssClass="text" Width="193px" AutoPostBack="True" OnSelectedIndexChanged="ddltabmenu_SelectedIndexChanged">
                                                    </asp:DropDownList></td>
                                                <td style="width: 100px">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 100px">
                                                    <asp:Label ID="Label2" runat="server" CssClass="boldtext" Text="Menu Item" Width="87px"></asp:Label></td>
                                                <td style="width: 100px">
                                                    <asp:TextBox ID="txtmenuitem" runat="server" Width="188px"></asp:TextBox></td>
                                                <td style="width: 100px">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 100px">
                                                    <asp:Label ID="Label5" runat="server" CssClass="boldtext" Text="Order No." Width="87px"></asp:Label></td>
                                                <td style="width: 100px">
                                                    <asp:TextBox ID="txtordermain" runat="server" Width="29px" MaxLength="3"></asp:TextBox>
                                                    <asp:RequiredFieldValidator ID="RequiredFieldValidator1" runat="server" ControlToValidate="txtordermain"
                                                        CssClass="text" ErrorMessage="*" ValidationGroup="a"></asp:RequiredFieldValidator>
                                                    <asp:RegularExpressionValidator ID="RegularExpressionValidator1" runat="server" ControlToValidate="txtordermain"
                                                        CssClass="text" Display="Dynamic" ErrorMessage="Only Integer" ValidationExpression="^[0-9]+$"
                                                        ValidationGroup="a"></asp:RegularExpressionValidator></td>
                                                <td style="width: 100px">
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="width: 100px">
                                                </td>
                                                <td>
                                                    <asp:Button ID="btnsavemainmenu" runat="server" CssClass="button" OnClick="btnsavemainmenu_Click"
                                                        Text="Save" ValidationGroup="a" />
                                                    <asp:Button ID="btnreset" runat="server" CssClass="button" OnClick="btnreset_Click"
                                                        Text="Cancel" /></td>
                                                <td style="width: 100px">
                                                </td>
                                            </tr>
                                        </table>
                                    </fieldset>
                                                    </td>
                                                </tr>
                                            </table>
                                        </asp:Panel>
                                        &nbsp;&nbsp;
                                    </td>
                                    <td colspan="2">
                                        &nbsp;<asp:Panel ID="pnlsubmenu" runat="server" Visible="False">
                                        <fieldset >
                                            <legend class="boldtext" >Add Sub Menu</legend>
                                            <asp:RequiredFieldValidator ID="RequiredFieldValidator4" runat="server" ControlToValidate="txtsubmenu"
                                                CssClass="text" ErrorMessage="*" ValidationGroup="b"></asp:RequiredFieldValidator><br />
                                            <table>
                                                <tr>
                                                    <td style="width: 100px">
                                                        <asp:Label ID="Label10" runat="server" CssClass="boldtext" Text="Tab " Width="89px"></asp:Label></td>
                                                    <td style="width: 100px">
                                                        <asp:DropDownList ID="ddltabsubmenu" runat="server" CssClass="text" Width="193px" AutoPostBack="True" OnSelectedIndexChanged="ddltabsubmenu_SelectedIndexChanged">
                                                        </asp:DropDownList></td>
                                                    <td style="width: 100px">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 100px">
                                                        <asp:Label ID="Label4" runat="server" CssClass="boldtext" Text="Menu Item" Width="89px"></asp:Label></td>
                                                    <td style="width: 100px">
                                                        <asp:DropDownList ID="ddlmenu" runat="server" AutoPostBack="True" CssClass="text"
                                                            OnSelectedIndexChanged="ddlmenu_SelectedIndexChanged" Width="193px">
                                                        </asp:DropDownList></td>
                                                    <td style="width: 100px">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 100px">
                                                        <asp:Label ID="Label3" runat="server" CssClass="boldtext" Text="Sub Menu Item" Width="107px"></asp:Label></td>
                                                    <td style="width: 100px">
                                                        <asp:TextBox ID="txtsubmenu" runat="server" Width="188px"></asp:TextBox></td>
                                                    <td style="width: 100px">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 100px">
                                                        <asp:Label ID="Label7" runat="server" CssClass="boldtext" Text="Order No." Width="87px"></asp:Label></td>
                                                    <td style="width: 100px">
                                                        <asp:TextBox ID="txtordersub" runat="server" Width="29px"></asp:TextBox>
                                                        <asp:RequiredFieldValidator ID="RequiredFieldValidator2" runat="server" ControlToValidate="txtordersub"
                                                            CssClass="text" ErrorMessage="*" ValidationGroup="b"></asp:RequiredFieldValidator><asp:RegularExpressionValidator
                                                                ID="RegularExpressionValidator2" runat="server" ControlToValidate="txtordersub"
                                                                CssClass="text" Display="Dynamic" ErrorMessage="Only Integer" ValidationExpression="^[0-9]+$"
                                                                ValidationGroup="b"></asp:RegularExpressionValidator></td>
                                                    <td style="width: 100px">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="width: 100px">
                                                    </td>
                                                    <td>
                                                        <asp:Button ID="btnsavesubmenu" runat="server" CssClass="button" OnClick="btnsavesubmenu_Click"
                                                            Text="Save" ValidationGroup="b" />
                                                        <asp:Button ID="btnresetsubmenu" runat="server" CssClass="button" OnClick="btnresetsubmenu_Click"
                                                            Text="Cancel" /></td>
                                                    <td style="width: 100px">
                                                    </td>
                                                </tr>
                                            </table>
                                        </fieldset>
                                        </asp:Panel>
                                    </td>
                                </tr>
                            </table><asp:Panel ID="pnltab"  runat="server" Visible="False">
                                &nbsp;<table>
                                    <tr>
                                        <td style="height: 174px">
                                            <fieldset style="valign: top" >
                                                <legend class="boldtext" >Add Tab</legend>
                                                <asp:RequiredFieldValidator ID="RequiredFieldValidator5" runat="server" ControlToValidate="txttabitem"
                                                    CssClass="text" ErrorMessage="*" ValidationGroup="a"></asp:RequiredFieldValidator><br />
                                                <table style="color: #000000">
                                                    <tr>
                                                        <td style="width: 100px">
                                                            <asp:Label ID="Label1" runat="server" CssClass="boldtext" Text="Tab Item" Width="87px"></asp:Label></td>
                                                        <td style="width: 100px">
                                                            <asp:TextBox ID="txttabitem" runat="server" Width="188px"></asp:TextBox></td>
                                                        <td style="width: 100px">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 100px">
                                                            <asp:Label ID="Label8" runat="server" CssClass="boldtext" Text="Order No." Width="87px"></asp:Label></td>
                                                        <td style="width: 100px">
                                                            <asp:TextBox ID="txttaborder" runat="server" MaxLength="3" Width="29px"></asp:TextBox>
                                                            <asp:RequiredFieldValidator ID="RequiredFieldValidator6" runat="server" ControlToValidate="txttaborder"
                                                                CssClass="text" ErrorMessage="*" ValidationGroup="a"></asp:RequiredFieldValidator>
                                                            <asp:RegularExpressionValidator ID="RegularExpressionValidator3" runat="server" ControlToValidate="txttaborder"
                                                                CssClass="text" Display="Dynamic" ErrorMessage="Only Integer" ValidationExpression="^[0-9]+$"
                                                                ValidationGroup="a"></asp:RegularExpressionValidator></td>
                                                        <td style="width: 100px">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td style="width: 100px">
                                                        </td>
                                                        <td>
                                                            <asp:Button ID="btntabsave" runat="server" CssClass="button"
                                                        Text="Save" ValidationGroup="a" OnClick="btntabsave_Click" />
                                                            <asp:Button ID="btntabreset" runat="server" CssClass="button"
                                                        Text="Cancel" OnClick="btntabreset_Click" />
                                                            <asp:Button ID="btndelete" runat="server" CssClass="button"
                                                        Text="Delete" ValidationGroup="a" Visible="False" OnClick="btndelete_Click" /></td>
                                                        <td style="width: 100px">
                                                        </td>
                                                    </tr>
                                                </table>
                                            </fieldset>
                                        </td>
                                    </tr>
                                </table>
                            </asp:Panel>
                        </td>
                    </tr>
                </table>
                </asp:Panel>
                                        <asp:Label ID="lbltargetnet" runat="server"></asp:Label><cc1:modalpopupextender id="ModalPopupExtendervip"
                                            runat="server" backgroundcssclass="modalBackground" 
                                            dropshadow="true" popupcontrolid="pnlmain" CancelControlID="lbltargetnet" targetcontrolid="lbltargetnet"> </cc1:modalpopupextender>
            </td>
        </tr>
        <tr>
            <td>
                </td>
        </tr>
        <tr>
            <td>
                &nbsp;</td>
        </tr>
        <tr>
            <td align="center">
                <asp:GridView ID="grvExisting" runat="server" AutoGenerateColumns="False" DataKeyNames="domainid"
                    Width="786px" CssClass="text" OnSelectedIndexChanging="grvExisting_SelectedIndexChanging" OnRowCommand="grvExisting_RowCommand" SkinID="gvip" OnRowDeleting="grvExisting_RowDeleting">
                    <Columns>
                        <asp:BoundField DataField="domainname" HeaderText="Domain" />
                        <asp:TemplateField>
                            <ItemTemplate><asp:GridView ID="gvinfirst" runat="server" AutoGenerateColumns="False" DataKeyNames="tabid" CssClass="text" OnRowCommand="grvExisting_RowCommand" SkinID="gvip">
                                <Columns>
                                    <asp:TemplateField HeaderText="Tab">
                                        <ItemTemplate>
                                            <asp:Label ID="lbltabname" runat="server" Text='<%# Eval("tabname") %>' Width="107px"></asp:Label>
                                            &nbsp;
                                        </ItemTemplate>
                                    </asp:TemplateField>
                                    <asp:TemplateField>
                                        <ItemTemplate>
                                            <asp:GridView ID="gvinsecond" runat="server" AutoGenerateColumns="False" DataKeyNames="menuid"
                    Width="311px" CssClass="text" OnRowCommand="gvinsecond_RowCommand" SkinID="gvip">
                                                <Columns>
                                                    <asp:TemplateField HeaderText="Menu">
                                                        <ItemTemplate>
                                                            <asp:Label ID="lblmenuname" runat="server" Text='<%# Eval("menuname") %>' Width="129px"></asp:Label>&nbsp;
                                                        </ItemTemplate>
                                                    </asp:TemplateField>
                        <asp:TemplateField HeaderText="Sub Menu">
                            <ItemTemplate><asp:GridView ID="gvinthird" runat="server" AutoGenerateColumns="False" DataKeyNames="menuid" CssClass="text" OnRowCommand="gvin_RowCommand" SkinID="gvip" Width="400px">
                                <Columns>
                                    <asp:BoundField DataField="menuname" HeaderText="Sub Menu" />
                                    <asp:BoundField DataField="orderno" HeaderText="Order" >
                                        <HeaderStyle HorizontalAlign="Center" />
                                        <ItemStyle HorizontalAlign="Center" />
                                    </asp:BoundField>
                                    <asp:TemplateField HeaderText="Status">
                                        <ItemTemplate>
                                            <asp:LinkButton ID="lnkstatus" runat="server" CommandArgument='<%# Eval("menuid") %>'
                                                CommandName="status" Text='<%# Eval("status") %>' Font-Bold="True" Font-Names="Rod" ForeColor="#006600"></asp:LinkButton>
                                        </ItemTemplate>
                                        <HeaderStyle HorizontalAlign="Center" />
                                        <ItemStyle HorizontalAlign="Center" />
                                    </asp:TemplateField>
                                     <asp:TemplateField>
                                        <ItemTemplate>
                                            <asp:ImageButton ID="ImageBuftton1" runat="server" CommandArgument='<%# Eval("menuid") %>'
                                                CommandName="sel" ImageUrl="~/Images/editbyvipan.jpg" />
                                        </ItemTemplate>
                                    </asp:TemplateField>
                                    <asp:TemplateField>
                                        <ItemTemplate>
                                            <asp:ImageButton ID="ImageButsaton1" runat="server" CommandArgument='<%# Eval("menuid") %>'
                                                CommandName="del" ImageUrl="~/Images/deletebyvipan.jpg" OnClientClick="Javascript:return confirm('Are You Sure You Want To Delete')" />
                                        </ItemTemplate>
                                    </asp:TemplateField>
                                </Columns>
                                <EmptyDataTemplate>
                                    .........
                                </EmptyDataTemplate>
                            </asp:GridView>
                            </ItemTemplate>
                        </asp:TemplateField>
                        <asp:BoundField DataField="orderno" HeaderText="Order" >
                            <HeaderStyle HorizontalAlign="Center" />
                            <ItemStyle HorizontalAlign="Center" />
                        </asp:BoundField>
                        <asp:TemplateField HeaderText="Status">
                            <ItemTemplate>
                                <asp:LinkButton ID="lnkstatus" runat="server" CommandName="status" Text='<%# Eval("status") %>' CommandArgument='<%# Eval("menuid") %>' Font-Bold="True" Font-Names="Rod" ForeColor="#006600"></asp:LinkButton>
                            </ItemTemplate>
                        </asp:TemplateField>
                                                    <asp:TemplateField>
                                                        <ItemTemplate>
                                                            <asp:ImageButton ID="ImageButton1" runat="server"
                                    CommandName="one" ImageUrl="~/Images/editbyvipan.jpg" ToolTip="Edit Menu Item" CommandArgument='<%# Eval("menuid") %>' /><asp:ImageButton ID="ImageButtogsan1" runat="server" CommandArgument='<%# Eval("menuid") %>'
                                    CommandName="two" ImageUrl="~/Images/deletebyvipan.jpg" OnClientClick="Javascript:return confirm('Are You Sure You Want To Delete')" ToolTip="Delete Menu Item" />
                                                        </ItemTemplate>
                                                    </asp:TemplateField>
                                                </Columns>
                                                <EmptyDataTemplate>
                                                    No Menu Found.........
                                                </EmptyDataTemplate>
                                            </asp:GridView>
                                        </ItemTemplate>
                                    </asp:TemplateField>
                                    <asp:BoundField DataField="orderno" HeaderText="Order" >
                                        <HeaderStyle HorizontalAlign="Center" />
                                        <ItemStyle HorizontalAlign="Center" />
                                    </asp:BoundField>
                                    <asp:TemplateField HeaderText="Status">
                                        <ItemTemplate>
                                            <asp:LinkButton ID="lnkstatus" runat="server" CommandArgument='<%# Eval("tabid") %>'
                                                CommandName="status" Font-Bold="True" Font-Names="Rod" ForeColor="#006600" Text='<%# Eval("status") %>'></asp:LinkButton>
                                        </ItemTemplate>
                                    </asp:TemplateField>
                                    <asp:TemplateField>
                                        <ItemTemplate>
                                            <asp:ImageButton ID="ImageButton1" runat="server" CommandArgument='<%# Eval("tabid") %>'
                                    CommandName="tab" ImageUrl="~/Images/editbyvipan.jpg" ToolTip="Edit Tab Item" /><asp:ImageButton ID="ImageButtogsan1" runat="server" CommandArgument='<%# Eval("tabid") %>'
                                    CommandName="deletetab" ImageUrl="~/Images/deletebyvipan.jpg" OnClientClick="Javascript:return confirm('Are You Sure You Want To Delete')" ToolTip="Delete Tab Item" />
                                        </ItemTemplate>
                                    </asp:TemplateField>
                                </Columns>
                                <EmptyDataTemplate>
                                    No Menu Found.........
                                </EmptyDataTemplate>
                            </asp:GridView>
                            </ItemTemplate>
                        </asp:TemplateField>
                    </Columns>
                    <EmptyDataTemplate>
                        No Menu Found.........
                    </EmptyDataTemplate>
                </asp:GridView>
            </td>
        </tr>
    </table>
</asp:Content>

