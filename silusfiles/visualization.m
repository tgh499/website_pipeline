
%% 
X=importMappedX('mappedX.csv',1,839);
Y=importColumn('Y.csv');
index=importColumn('Y_index.csv',1,5);
index=index+1;
n_p = length(Y);
%% 3D
% If data is in 3D, run this section, skip next one
figure('position',[100,0,800,600]);
mkSize = 35;
i=index(1);
j=index(2)-1;
scatter3(X(i:j,1), X(i:j,2), X(i:j,3), mkSize,'o');   
hold on;  
i=index(2);
j=index(3)-1;
scatter3(X(i:j,1), X(i:j,2), X(i:j,3), mkSize,'+');

i=index(3);
j=index(4)-1;
scatter3(X(i:j,1), X(i:j,2), X(i:j,3), mkSize,'*');

i=index(4);
j=index(5)-1;
scatter3(X(i:j,1), X(i:j,2), X(i:j,3), mkSize,'s','filled');
i=index(5);
j=n_p;
scatter3(X(i:j,1), X(i:j,2), X(i:j,3), mkSize,'x'); 

box on;
ax = gca;
ax.LineWidth = 2;
ax.FontSize = 20;
ax.FontWeight ='bold';
ax.ZLim = [-7 7];
led=legend('show','Normal','Basal','Her2','LumA','LumB');
led.FontSize=20;
%% 2D 
% If data is in 2D, run this section, skip previous one
figure('position',[100,0,800,600]);
mkSize = 45;
i=index(1);
j=index(2)-1;
scatter(X(i:j,1), X(i:j,2), mkSize,'o');   
hold on;  
i=index(2);
j=index(3)-1;
scatter(X(i:j,1), X(i:j,2), mkSize,'+');

i=index(3);
j=index(4)-1;
scatter(X(i:j,1), X(i:j,2), mkSize,'*');

i=index(4);
j=index(5)-1;
scatter(X(i:j,1), X(i:j,2), mkSize,'s','filled');
i=index(5);
j=n_p;
scatter(X(i:j,1), X(i:j,2), mkSize,'x'); 

box on;
ax = gca;
ax.LineWidth = 2;
ax.FontSize = 20;
ax.FontWeight ='bold';
ax.XLim = [-12 12];
ax.YLim = [-12 12];
led=legend('show','Normal','Basal','Her2','LumA','LumB');
led.FontSize=20;
ax.XLabel.String = 'x1';
ax.YLabel.String = 'x2';
ax.Position = [0.08 0.1 0.9 0.9];

