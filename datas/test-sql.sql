# getAll() from TheNewsManager  
SELECT * FROM TheNews ORDER BY theNewsDate DESC

# getAllHomePage() from TheNewsManager
SELECT n.theNewsTitle, n.theNewsSlug, LEFT(n.theNewsText,180) AS theNewsText, n.theNewsDate, n.theUserIdtheUser,
                    u.theUserLogin,
                    GROUP_CONCAT(s.theSectionName SEPARATOR '|||') as theSectionName, GROUP_CONCAT(s.idtheSection) as idtheSection
                FROM TheNews n 
                    INNER JOIN TheUser u
                        ON u.idtheUser = n.theUserIdtheUser
                    LEFT JOIN theNews_has_theSection h
                        ON h.theNews_idtheNews = n.idtheNews
                    LEFT JOIN thesection s 
                        ON s.idtheSection = h.theSection_idtheSection
                GROUP BY n.idtheNews
                ORDER BY n.theNewsDate DESC;

# getAllNewsInTheSection from TheNewsManager
SELECT n.idtheNews, n.theNewsTitle, n.theNewsSlug, LEFT(n.theNewsText,180) AS theNewsText, n.theNewsDate, n.theUserIdtheUser,
                    u.theUserLogin,
                    
                        (SELECT GROUP_CONCAT(s2.theSectionName ,'|||', s2.idtheSection SEPARATOR '---')
                            FROM thesection s2
                            INNER JOIN theNews_has_theSection h2
                                ON s2.idtheSection = h2.theSection_idtheSection
                            INNER JOIN TheNews n2    
                                ON n2.idtheNews = h2.theNews_idtheNews
                            WHERE   n.idtheNews = n2.idtheNews  
                        )AS section
                        
                FROM TheNews n 
                    INNER JOIN TheUser u
                        ON u.idtheUser = n.theUserIdtheUser
                    LEFT JOIN theNews_has_theSection h
                        ON h.theNews_idtheNews = n.idtheNews
                    LEFT JOIN thesection s 
                        ON s.idtheSection = h.theSection_idtheSection
                WHERE s.idtheSection = 3        
                GROUP BY n.idtheNews
                ORDER BY n.theNewsDate DESC;

SELECT * FROM theuser;

SELECT u.`idtheUser`,u.`theUserLogin`,u.`theUserMail`,r.theRoleName,r.`theRoleValue`
    FROM theuser u  
    INNER JOIN therole r
        ON u.`theRoleIdtheRole`= r.idtheRole
    WHERE u.theUserLogin='Mikhawa' AND u.theUserPwd='Mikhawa1717';
