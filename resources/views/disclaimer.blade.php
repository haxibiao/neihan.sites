@extends('layouts.app')

@section('content')

    <div id="detail">
        <div class="main">
            <article>
                <div class="show-content">
                    <p></p>
                    <p></p>
                    <h2 style="margin-left: 0cm; text-align: center;">问答细则及责任声明</h2>
                    <p style="text-align: justify;">&nbsp;</p>
                    <p style="margin-left: 0cm;">{{seo_site_name()}}致力于为知友提供一个有着良好氛围和高质量讨论的问答社区。问和答，一个好的问题是回答创作的基础。为了更好地讨论，形成有效的问答，现规范{{seo_site_name()}}的提问原则、界定{{seo_site_name()}}不鼓励的提问类型和相应的处罚措施。</p>
                    <p style="margin-left: 0cm;">&nbsp;</p>
                    <h2 style="margin-left: 0cm;">一、在{{seo_site_name()}}提问应遵循什么原则？</h2>
                    <p style="margin-left: 0cm;">{{seo_site_name()}}上提问应遵循真实、客观、简洁、明确、规范的原则。</p>
                    <ul>
                        <li>真实：自己思考之后的真实疑惑，不为了提问而提问；</li>
                        <li>客观：理性客观地陈述问题和事实，不偏激；</li>
                        <li>简洁：用简洁的文字提炼问题，不赘述；</li>
                        <li>明确：表意清晰、有针对性；</li>
                        <li>规范：标点符号、词语和句式严谨规范。</li>
                    </ul>
                    <p style="text-align: justify;">&nbsp;</p>
                    <h2 style="margin-left: 0cm;">二、什么是{{seo_site_name()}}不鼓励的提问？</h2>
                    <p style="margin-left: 0cm;">除了{{seo_site_name()}}协议中提到和界定的违规类型，在{{seo_site_name()}}发布问题，仍需避免以下类型的提问：</p>
                    <p style="margin-left: 0cm;">1. 寻人、征友、作业等个人任务类问题</p>
                    <p style="margin-left: 0cm;">2. 不构成一个提问</p>
                    <p style="margin-left: 0cm;">3. 提问缺乏可信来源</p>
                    <p style="margin-left: 0cm;">4. 提问包含主观判断</p>
                    <p style="margin-left: 0cm;">5. 引战争议等非真实问题</p>
                    <p style="margin-left: 0cm;">6. 针对具体病情的求医问药类问题</p>
                    <p style="margin-left: 0cm;">7. 寻求危害生命安全的方法类问题</p>
                    <p style="margin-left: 0cm;">8. 看相、算命、星盘等封建迷信问题</p>
                    <p style="margin-left: 0cm;">9. 其他常见的不鼓励类型</p>
                    <p style="margin-left: 0cm;">以下是各类型不鼓励的提问的具体界定和举例说明：</p>
                    <h3 style="margin-left: 0cm;">2.1 寻人、征友、作业等个人任务类问题</h3>
                    <ul>
                        <li>{{seo_site_name()}}鼓励大家提出经过思考并有一定讨论价值的提问，这种提问可以形成讨论并对他人有一定的参考价值，不鼓励用户以提问的形式在{{seo_site_name()}}达到征求作业答案、招聘、帮忙填写调查问卷、寻人征友等目的。</li>
                    </ul>
                    <ul>
                        <li>特别需要关注的是，在一些科学领域，如数学、物理学等学科下经常会有一些看似基础的、具有个人任务（作业题）特征的问题。在这些问题下，也会有用户认真写回答，提问本身讨论的问题也并非毫无价值。因此以上类型的提问不会被直接关闭，一些问法不规范的提问，鼓励大家通过公共编辑改进。</li>
                    </ul>
                    <h3 style="margin-left: 0cm;">2.2 不构成一个提问</h3>
                    <ul>
                        <li>不构成提问，即问题表意不清晰。如问题标题只是一个词语、短语或陈述句而非一个完整、真实的疑问句，或者问题存在歧义或缺少关键信息导致其他人无法针对该提问进行回答。</li>
                    </ul>
                    <h3 style="margin-left: 0cm;">2.3 提问缺乏可信来源</h3>
                    <ul>
                        <li>缺乏可信来源的提问是指未提供可靠的信息来源、未证实、或提问前提存在错误的提问，需要个人提供关键信息或者讨论背景来源。</li>
                    </ul>
                    <h3 style="margin-left: 0cm;">2.4 提问包含主观判断</h3>
                    <ul>
                        <li>问题中包含提问者无根据的非常主观甚至引发争议的见解、结论或推测，使提问不能基于一个客观中立的事实或前提进行讨论。</li>
                    </ul>
                    <h3 style="margin-left: 0cm;">2.5 引战争议等非真实问题</h3>
                    <ul>
                        <li>刻意挑起群体或个人之间的对立、冲突、激化矛盾的问题可以认为是引战问题。通过提问的形式捏造、编写一个非真实的场景来诱使他人上当从而满足提问者的恶趣味或达到引战的目的，这类问题可以认为是非真实类问题，此类问题不是在自己思考之后的真实疑惑，并非为了解决问题而提问。</li>
                    </ul>
                    <h3 style="margin-left: 0cm;">2.6 针对具体病情的求医问药类问题</h3>
                    <p style="margin-left: 0cm;">是指包含个人病情的具体信息，寻求针对该病情的治疗方案、手段、治疗地点、医生、药物等的问题。</p>
                    <ul>
                        <li>在{{seo_site_name()}}寻求医疗建议一方面回答也许不够及时，另一方面回答者不一定有医学背景或相关医疗资质，因此为避免求医者延误病情甚至被误导，我们建议知友到医院或有资质的医疗机构向医生寻求帮助。</li>
                    </ul>
                    <h3 style="margin-left: 0cm;">2.7 寻求危害生命安全的方法类问题</h3>
                    <p style="margin-left: 0cm;">在社区内发现有知友有疑似极端意图或危险行为甚至自杀倾向时，我们会根据{{seo_site_name()}}危机干预流程，第一时间收集相应信息并上报至有关部门，并持续关注，愿每位知友都平安，减少悲剧的发生。</p>
                    <ul>
                        <li>因此，为了保护知友们的生命安全，{{seo_site_name()}}也同样不允许用户在社区以提问的方式发布可能引发危险行为的问题，比如：寻求自杀或自残的方法、询问自杀自残体验，以免被人模仿进而扩大伤害范围。</li>
                    </ul>
                    <h3 style="margin-left: 0cm;">2.8 看相、算命、星盘等封建迷信问题</h3>
                    <ul>
                        <li>包含使用算命、测字、占卜、解梦、风水等迷信方式治病、卜问前程姻缘、化解厄运等等。</li>
                    </ul>
                    <h3 style="margin-left: 0cm;">2.9 其他常见不鼓励的提问类型</h3>
                    <p style="margin-left: 0cm;">2.9.1 容易引发大量身体暴露图片、色情图片、性行为描写的问题，如：女生穿情趣内衣是一种怎样的体验？</p>
                    <p style="margin-left: 0cm;">2.9.2 易引发盗版资源、违法信息等相关回答的问题，如：如何破解别人的 QQ 设备锁？</p>
                    <p style="margin-left: 0cm;">2.9.3 探寻他人的隐私信息类问题，如：XX 是不是 Gay？</p>
                    <p style="margin-left: 0cm;">2.9.4 人品道德评价类提问，{{seo_site_name()}}鼓励用户讨论公众人物在其专业领域的作品及言行，或者对有可靠新闻来源的言行的讨论，即使是某公众人物吸毒、出轨、家暴，也不鼓励直接针对其人品道德进行讨论。如：XX 人品怎么样？</p>
                    <p style="margin-left: 0cm;">&nbsp;</p>
                    <h2 style="margin-left: 0cm;">三、以上类型的不鼓励提问会被如何处理？</h2>
                    <p style="margin-left: 0cm;">看相、算命等封建迷信类问题，寻求自杀自残方法体验类问题，会视严重程度对提问进行删除或关闭，被关闭的问题不再被允许添加新回答，被关闭的问题和问题下回答不会被推荐。</p>
                    <p style="margin-left: 0cm;">寻人、征友、作业等个人任务类问题，引战争议等非真实问题，求医问药类问题，一旦被发现或举报，提问会被关闭。</p>
                    <p style="margin-left: 0cm;">不构成提问、缺乏可信来源、包含主观判断类问题，会视严重程度对提问进行建议修改或关闭。被建议修改的问题，在提问者或其他拥有公共编辑权限的用户对提问进行修改后自动进入评估状态，如果修改后问题符合提问规范则会恢复为正常状态。如果一周之内问题没有经过修改恢复正常则会自动关闭。</p>
                    <p style="margin-left: 0cm;">其他常见不鼓励的提问类型，会视严重程度进行删除或关闭处理。</p>
                    <p style="margin-left: 0cm;">&nbsp;</p>
                    <h2 style="margin-left: 0cm;">四、提出违反规范提问的用户是否会被处罚？</h2>
                    <p style="margin-left: 0cm;">提出以上类型提问的用户，若提问动机并非恶意，原则上不会处罚提问者。如发现提问者存在恶意主观动机，{{seo_site_name()}}会根据是否多次、提问造成的影响范围等维度对提问者进行禁言甚至永久禁言的处罚。</p>
                    <p style="margin-left: 0cm;">&nbsp;</p>
                    <p style="margin-left: 0cm;"><b>五、</b><b>免责说明</b></p>
                    <p style="text-align: justify;"><span style="color: rgb(18, 18, 18);">{{seo_site_name()}}不能对用户发表的回答或评论的正确性进行保证。</span></p>
                    <p style="text-align: justify;"><span style="color: rgb(18, 18, 18);">用户在{{seo_site_name()}}发表的内容仅表明其个人的立场和观点，并不代表{{seo_site_name()}}的立场或观点。作为内容的发表者，需自行对所发表内容负责，因所发表内容引发的一切纠纷，由该内容的发表者承担全部法律及连带责任。{{seo_site_name()}}不承担任何法律及连带责任。</span></p>
                    <p style="text-align: justify;"><span style="color: rgb(18, 18, 18);">{{seo_site_name()}}不保证网络服务一定能满足用户的要求，也不保证网络服务不会中断，对网络服务的及时性、安全性、准确性也都不作保证。</span></p>
                    <p><span style="color: rgb(18, 18, 18);">对于因不可抗力或{{seo_site_name()}}不能控制的原因造成的网络服务中断或其它缺陷，{{seo_site_name()}}不承担任何责任，但将尽力减少因此而给用户造成的损失和影响。</span></p>
                    <p></p>
                    <p></p>
                </div>
            </article>
        </div>
    </div>

@endsection
